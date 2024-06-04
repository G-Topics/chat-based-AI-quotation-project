<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    public $timestamps = false;

    protected $fillable = [
        'id','nombre','descripcion','modo_de_accion','precio','id_clasificacion'
    ];

    public function inventarios()
    {
        return $this->belongsTo(InventarioSucursalAlmacen::class);
    }

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }
    public function detalles()
    {
        return $this->belongsTo(Detalle::class);
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}
