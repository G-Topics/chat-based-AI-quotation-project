<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    protected $table = 'cultivos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_tipo_cultivo',
    ];

    public function tipoCultivo()
    {
        return $this->belongsTo(TipoCultivo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sintomas()
    {
        return $this->hasMany(Sintoma::class);
    }
}
