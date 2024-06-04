<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';
    public $timestamps = false;
    protected $fillable = ['id','m_recibido' ,'m_enviado','fecha','id_cliente'];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'id_cliente');   
    }
}