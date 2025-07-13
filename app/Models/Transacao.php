<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $primaryKey = 'id_transacao';

    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'tipo',
        'id_categoria',
        'id_usuario',
    ];

    // Relação: Uma transação pertence a um usuário
    public function users()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

}
