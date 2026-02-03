<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ModeloController extends Controller
{

    private $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);

        if ($request->has("atributos_marca")) {
            $atributos_marca = 'marca:id,' . $request->get('atributos_marca');
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        }else {
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->get('filtro'));
        }

        if ($request->has('atributos')) {
            $atributos = $request->get('atributos');
            $modeloRepository->selectAtributos($atributos);
        }

        return response()->json($modeloRepository->getResultados(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $modelo = $this->modelo;
        $request->validate($modelo->rules(), $modelo->feedback());

        $imagem = $request->file("imagem");
        $imagem_urn = $imagem->store('imagem/modelo', 'public');

        $modelo->create([
            'marca_id' => $request->input('marca_id'),
            'nome' => $request->input('nome'),
            'imagem' => $imagem_urn,
            'numero_portas' => $request->input('numero_portas'),
            'lugares' => $request->input('lugares'),
            'air_bag' => $request->input('air_bag'),
            'abs' => $request->input('abs'),
        ]);

        return response()->json(["success" => "Registro cadastrado com sucesso."], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if ($modelo === null) {
            return response()->json(["error", "Recurso pesquisado não existe"], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modelo $modelo)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(["error", "Impossível realizar a atualização. O recurso solicitado não existe"], 404);
        }

        $regras_dinamica = array();
        if ($request->method() === "PATCH") {

            foreach ($modelo->rules() as $key => $regras) {

                if (array_key_exists($key, $request->all())) {
                    $regras_dinamica[$key] = $regras;
                }

            }

            $request->validate($regras_dinamica, $modelo->feedback());

        } else {

            $request->validate($modelo->rules(), $modelo->feedback());

        }

        $modelo->fill($request->all());

        if ($request->hasFile('imagem')) {


            if ($modelo->imagem) {
                Storage::disk('public')->delete(paths: $modelo->imagem);
            }
            ;

            $modelo->imagem = $request->file('imagem')->store('imagem/modelo', 'public');
        }


        $modelo->save();

        return response()->json(['data' => $modelo], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(["error", "Impossível realizar a exclusão. O recurso solicitado não existe"], 404);
        }

        $imagem_url = $modelo->imagem;
        Storage::disk("public")->delete($imagem_url);

        $modelo->delete();

        return response()->json(['Success', 'Registro removido com sucesso', 201]);
    }
}
