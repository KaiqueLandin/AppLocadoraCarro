<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    //
    public function locacao(){
        return $this->hasMany(locacao::class);
    }
}
