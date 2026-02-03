<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = "modelos";
    protected $fillable = ["marca_id", "nome", "imagem", "numero_portas", "lugares", "air_bag", "abs"];
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    
    public function rules()
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => 'required|min:3|unique:modelos,nome,' . $this->id,
            'imagem' => 'required|file|mimes:png,jpg,jpeg|max:2024',
            'numero_portas' => 'required|integer|digits_between:0,1',
            'lugares' => 'required|integer|digits_between:0,1',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }
    public function feedback()
    {
        return [
            'marca_id.required' => 'A marca deve ser informada.',
            'marca_id.exists' => 'A marca selecionada é inválida.',

            'nome.required' => 'O nome do modelo deve ser informado.',
            'nome.min' => 'O nome do modelo deve ter no mínimo :min caracteres.',
            'nome.unique' => 'Já existe um modelo cadastrado com esse nome.',

            'imagem.required' => 'A imagem do modelo deve ser enviada.',
            'imagem.file' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar no formato PNG, JPG ou JPEG.',
            'imagem.max' => 'A imagem não pode ultrapassar :max KB.',

            'numero_portas.required' => 'O número de portas deve ser informado.',
            'numero_portas.integer' => 'O número de portas deve ser um valor inteiro.',
            'numero_portas.digits_between' => 'O número de portas deve ter entre :min e :max dígitos.',

            'lugares.required' => 'A quantidade de lugares deve ser informada.',
            'lugares.integer' => 'A quantidade de lugares deve ser um valor inteiro.',
            'lugares.digits_between' => 'A quantidade de lugares deve ter entre :min e :max dígitos.',

            'air_bag.required' => 'Informe se o modelo possui air bag.',
            'air_bag.boolean' => 'O campo air bag deve ser verdadeiro ou falso.',

            'abs.required' => 'Informe se o modelo possui ABS.',
            'abs.boolean' => 'O campo ABS deve ser verdadeiro ou falso.',
        ];
    }

}
