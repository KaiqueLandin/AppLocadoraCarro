<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ModeloRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributoMarca($atributos_marca)
    {
        $this->model = $this->model->with($atributos_marca);
    }

    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAtributos($atributos)
    {
      $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultados(){
        return $this->model->get();
    }

}

?>