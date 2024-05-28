<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    public $timestamps = false;
    protected $fillable = ['id','nombre' ,'telefono'];

    public function historiales(){
        return $this->hasMany(HistorialChat::class,'id_cliente');   
    }
}
