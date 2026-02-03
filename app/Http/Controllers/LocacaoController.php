<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;
use Mockery\Generator\Method;

class LocacaoController extends Controller
{
    protected $locacao;
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has("atributos_cliente")) {
            $locacaoRepository->selectAtributosRegistrosRelacionados('cliente:id,' . $request->atributos_cliente);
        } else {
            $locacaoRepository->selectAtributosRegistrosRelacionados('cliente');
        }

        if ($request->has('filtro')) {
            $locacaoRepository->selectAtributosRegistrosRelacionados($request->filtro);
        }

        if ($request->has('atributos')) { 
                $locacaoRepository->selectAtributos($request->atributos);
            }

            return response()->json($locacaoRepository->getResultados(), 200);
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
        $locacao = $this->locacao->create($request->validate($this->locacao->rules(), $this->locacao->feedbeck()));
        return response()->json($locacao, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $locacao = $this->locacao->with('cliente')->findOrFail($id);
        if($locacao == null) {
            return response()->json(['error' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao == null) {
            return response()->json(['error' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }   

        if($request->method() === 'PATCH'){

            $regrasDinamicas = [];
            foreach($locacao->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $locacao->update($request->validate($regrasDinamicas, $this->locacao->feedbeck()));
        } else {
            $locacao->update($request->validate($this->locacao->rules(), $this->locacao->feedbeck()));
        }

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao == null) {
            return response()->json(['error' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }
        $locacao->delete();
        return response()->json(['msg' => 'Locação removida com sucesso!'], 200);
    }
}
