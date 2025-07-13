<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInvestimento extends Model
{
    use HasFactory;

    protected $fillable = ['tipo'];

    public function investimentos()
    {
        return $this->hasMany(Investimento::class, 'id_tipo_investimento');
    }
}
