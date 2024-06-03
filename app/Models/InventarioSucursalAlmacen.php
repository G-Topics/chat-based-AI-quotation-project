<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSucursalAlmacen extends Model
{
    protected $table = 'inventario_sucursal_almacen';
    public $timestamps = false;

    protected $fillable = [
        'cantidad_en_inventario',
        'fecha_ultima_actualizacion',
        'id_producto',
        'id_almacen',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}
