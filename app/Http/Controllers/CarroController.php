<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    protected $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has("atributos_carros")) {
            $detalhes = 'modelo:id,'.$request->atributos_carros;
            $carroRepository->selectAtributosRegistrosRelacionados($detalhes);
        }else{
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if ($request->has('filtro')) {
            $detalhes = $request->filtro;
            $carroRepository->filtro($detalhes);
        }

        if ($request->has('selectAtributos')) {
            $detalhes = $request->selectAtributos;
            $carroRepository->selectAtributos($detalhes);
        }

        return response()->json($carroRepository->getResultados(), 200);
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
        $carro = $this->carro;
        $carro->create($request->validate($this->carro->rules(), $this->carro->feedbeck()));

        return response()->json(['success' => 'Registro criado com sucesso'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);

        if ($carro == null) {
            return response()->json(['error' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if ($carro == null) {
            return response()->json(['error' => 'Impossível realizar a atualização. Recurso não existe'], 404);
        }

        if ($request->method() === 'PATCH') {
            $ValidacaoDinamica = array();
            foreach ($carro->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $ValidacaoDinamica[$key] = $value;
                }
            }

            $carro->update($request->validate($ValidacaoDinamica));
        }else{
            $carro->update($request->all());
        }

        

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if($carro == null) {
            return response()->json(['error' => 'Impossível realizar a exclusão. Recurso  não existe'], 404);
        }

        $carro->delete();

        return response()->json(['success' => 'Registro excluído com sucesso'], 200);
    }
}
