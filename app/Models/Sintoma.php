<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sintoma extends Model
{
    protected $table = 'sintoma';
    public $timestamps = false;
    protected $fillable = ['id','descripcion' ,'imagen','id_enfermedad','id_cultivo'];

    public function cultivo(){
        return $this->belongsTo(Cultivo::class,'id_cultivo');   
    }
    public function enfermedad(){
        return $this->belongsTo(Enfermedad::class,'id_enfermedad');   
    }
}
