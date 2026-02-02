<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = "marcas";
    protected $fillable = ["nome", "imagem"];

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }

    public function rules()
    {
        return [
            "nome" => "required|min:2|max:50|unique:marcas,nome," . $this->id,
            "imagem" => "required|file|mimes:png,jpg,jpeg|max:2024",
        ];
    }
    public function feedback()
    {
        return [
            'nome.required' => 'Campo :attribute deve ser preenchido.',
            'nome.min' => 'Campo :attribute deve ter no mínimo :min caracteres.',
            'nome.max' => 'Campo :attribute deve ter no máximo :max caracteres.',
            'nome.unique' => 'O nome da marca já existe.',

            'imagem.required' => 'Imagem da marca deve ser carregada.',
            'imagem.file' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar no formato: png, jpg ou jpeg.',
            'imagem.max' => 'A imagem não pode ultrapassar :max KB.',
        ];
    }

}
