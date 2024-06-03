<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;
    protected $fillable = ['nombre','id' ,'telefono','email'];

    public function historiales(){
        return $this->hasMany(HistorialChat::class,'id_cliente');   
    }

    public function cultivos(){
        return $this->hasMany(Cultivo::class,'id_cliente');   
    }
}
