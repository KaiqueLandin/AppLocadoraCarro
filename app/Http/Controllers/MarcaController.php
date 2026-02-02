<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $marca, $rules, $feedback, $id;
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
           $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultados(), 200);
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
        $request->validate($this->marca->rules(), $this->marca->feedback());


        $imagem = $request->file("imagem");
        $imagem_urn = $imagem->store("imagem/marca", "public");

        $marca = $this->marca->create([
            "nome" => $request->input("nome"),
            "imagem" => $imagem_urn
        ]);

        return response()->json(["success" => $marca], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            return response()->json(["error", "Recurso pesquisado não existe"], 404);
        }

        return response()->json($marca, 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(["error", "Impossível realizar a atualização. O recurso solicitado não existe"], 404);
        }

        if ($request->method() === "PATCH") {

            $regrasDinamicas = array();

            foreach ($marca->rules($id) as $input => $regra) {

                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $this->marca->feedback());

        } else {
            $request->validate($this->marca->rules(), $this->marca->feedback());
        }

        $marca->fill($request->all());

        if ($request->hasFile('imagem')) {

            // remove imagem antiga (se existir)
            if ($marca->imagem) {
                Storage::disk('public')->delete($marca->imagem);
            }

            // salva nova imagem
            $imagem = $request->file('imagem');
            $marca->imagem = $imagem->store('imagem/marca', 'public');
        }

        $marca->save();

        return response()->json(["success" => $marca], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(["error", "Impossível realizar a exclusão. O recurso solicitado não existe"], 404);
        }


        $caminho = $marca->imagem;
        Storage::disk('public')->delete($caminho);


        $marca->delete();

        return response()->json(["success" => "marca excluida com sucesso!"], 200);
    }
}
