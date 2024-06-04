<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quimico extends Model
{
    protected $table = 'quimico';
    public $timestamps = false;
    protected $fillable = ['id','formula' ,'nombre'];

    public function detalle(){
        return $this->hasMany(Detalle::class,'id_quimico');    
    }
}
