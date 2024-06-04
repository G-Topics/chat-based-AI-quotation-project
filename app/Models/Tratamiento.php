<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tratamiento';
    public $timestamps = false;
    protected $fillable = ['id','descipcion' ,'id_enfermedad','id_producto'];

    public function enfermedad(){
        return $this->belongsTo(Enfermedad::class,'id_enfermedad');   
    }
    public function producto(){
        return $this->belongsTo(Producto::class,'id_producto');   
    }
}
