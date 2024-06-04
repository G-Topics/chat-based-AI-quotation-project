<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'enfermedad';
    public $timestamps = false;
    protected $fillable = ['id','nombre' ,'nombre_cientifico','descripcion'];

    public function sintomas(){
        return $this->hasMany(Sintoma::class,'id_enfermedad');   
    }
}
