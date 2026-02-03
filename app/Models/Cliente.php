<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";
    protected $fillable = ["nome"];

    public function carros(){
        return $this->hasMany(Carro::class);
    }

    public function locacoes()
    {
        return $this->hasMany(Locacao::class);
    }

    public function rules(){
        return [
            'nome' => 'required|min:3|max:100',
        ];
    }
    public function feedbeck(){
        return [
            'nome.required' => 'O nome do cliente deve ser informado.',
            'nome.min' => 'O nome do cliente deve ter no mínimo :min caracteres.',
            'nome.max' => 'O nome do cliente deve ter no máximo :max caracteres.',
        ];
    }
}
