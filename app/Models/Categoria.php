<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nome'];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'id_categoria');
    }
}
