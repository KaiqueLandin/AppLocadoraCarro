<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    public function locacoes()
    {
        return $this->hasMany(Locacao::class);
    }
}
