<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialChat extends Model
{
    protected $table = 'historial_chat';
    public $timestamps = false;
    protected $fillable = ['id','mensaje' ,'fecha','id_cultivo','id_cliente','role'];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'id_cliente');   
    }
}