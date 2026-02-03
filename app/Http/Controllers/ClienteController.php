<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    protected $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRespository = new ClienteRepository($this->cliente);

        if ($request->has('atributos_locacoes')) {
            $detalhes = $request->atributos_locacoes;
            $clienteRespository->selectAtributosRegistrosRelacionados($detalhes);
        }

        if ($request->has('filtro')) {
            $clienteRespository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $clienteRespository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRespository->getResultados(), 200);
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
        $this->cliente->create(
            $request->validate(
                $this->cliente->rules(),
                $this->cliente->feedbeck()
            )
        );
        return response()->json(['success' => 'Registro criado com sucesso!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['error' => 'Registro não encontrado'], 404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['error' => 'Registro não encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($cliente->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $cliente->update($request->validate($regrasDinamicas, $this->cliente->feedbeck()));
        } else {
            $cliente->update($request->validate($cliente->rules(), $this->cliente->feedbeck()));
        }

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(['error' => 'Impossível excluir, registro não encontrado'], 404);
        }
        $cliente->delete();
        return response()->json(['success' => 'Registro excluído com sucesso!'], 200);
    }
}
