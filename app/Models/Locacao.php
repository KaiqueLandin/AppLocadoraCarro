<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    protected $table = "locacoes";
    protected $fillable = [
        "cliente_id",
        "carro_id",
        "data_inicio_periodo",
        "data_final_previsto_periodo",
        "data_final_realizado_periodo",
        "valor_diaria",
        "km_inicial",
        'km_final',
        'valor_total'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'data_inicio_periodo' => 'required|date',
            'data_final_previsto_periodo' => 'nullable|date|after_or_equal:data_inicio_periodo',
            'data_final_realizado_periodo' => 'nullable|date|after_or_equal:data_inicio_periodo',
            'valor_diaria' => 'required|numeric',
            'km_inicial' => 'required|integer',
            'km_final' => 'nullable|integer|gte:km_inicial',
            'valor_total' => 'nullable|numeric',
        ];
    }
    public function feedbeck()
    {
        return [
            'cliente_id.required' => 'O ID do cliente é obrigatório.',
            'cliente_id.exists' => 'O cliente informado não existe.',

            'carro_id.required' => 'O ID do carro é obrigatório.',
            'carro_id.exists' => 'O carro informado não existe.',

            'data_inicio_periodo.required' => 'A data de início do período é obrigatória.',
            'data_inicio_periodo.date' => 'A data de início do período deve ser uma data válida.',

            'data_final_previsto_periodo.date' => 'A data final prevista do período deve ser uma data válida.',
            'data_final_previsto_periodo.after_or_equal' => 'A data final prevista do período deve ser igual ou posterior à data de início do período.',

            'data_final_realizado_periodo.date' => 'A data final realizada do período deve ser uma data válida.',
            'data_final_realizado_periodo.after_or_equal' => 'A data final realizada do período deve ser igual ou posterior à data de início do período.',

            'valor_diaria.required' => 'O valor da diária é obrigatório.',
            'valor_diaria.numeric' => 'O valor da diária deve ser um número válido.',

            'km_inicial.required' => 'A quilometragem inicial é obrigatória.',
            'km_inicial.integer' => 'A quilometragem inicial deve ser um número inteiro.',

            'km_final.integer' => 'A quilometragem final deve ser um número inteiro.',
            'km_final.gte' => 'A quilometragem final deve ser maior ou igual à quilometragem inicial.',

            'valor_total.numeric' => 'O valor total deve ser um número válido.',
        ];
    }
}
