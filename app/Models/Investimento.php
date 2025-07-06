<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_investimento';

    protected $fillable = [
        'id_tipo_investimento',
        'instituicao',
        'data',
        'valor',
        'rendimento_esperado',
        'id_usuario',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function tipoInvestimento()
    {
        return $this->belongsTo(TipoInvestimento::class, 'id_tipo_investimento');
    }
}
