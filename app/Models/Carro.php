<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $fillable = ["modelo_id", "placa", "disponivel", "km"];
    protected $table = "carros";

    public function locacao(){
        return $this->hasMany(Locacao::class);
    }

    public function modelo(){
        return $this->belongsTo(Modelo::class);
    }

    public function rules(){
        return [
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => 'required|min:7|max:7|unique:carros,placa,' . $this->id,
            'disponivel' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }
    public function feedbeck(){
        return [
            'modelo_id.required' => 'O modelo deve ser informado.',
            'modelo_id.exists' => 'O modelo selecionado é inválido.',

            'placa.required' => 'A placa do carro deve ser informada.',
            'placa.min' => 'A placa do carro deve ter :min caracteres.',
            'placa.max' => 'A placa do carro deve ter no máximo :max caracteres.',
            'placa.unique' => 'Já existe um carro cadastrado com essa placa.',

            'disponivel.required' => 'O status de disponibilidade deve ser informado.',
            'disponivel.boolean' => 'O campo disponível deve ser verdadeiro ou falso.',

            'km.required' => 'A quilometragem do carro deve ser informada.',
            'km.integer' => 'A quilometragem do carro deve ser um valor inteiro.',
        ];
    }
}
