<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCultivo extends Model
{
    protected $table = 'tipo_cultivos';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }
}
