<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    public $timestamps = false;

    protected $fillable = [
        'capacidad_almacenamiento',
        'encargado_almacen',
        'id_sucursal',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function inventarioSucursalAlmacen()
    {
        return $this->hasMany(InventarioSucursalAlmacen::class);
    }
}
