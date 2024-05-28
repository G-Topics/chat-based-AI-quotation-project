<?php

namespace App\Services;

use App\Models\Cliente;

use Illuminate\Support\Facades\Log;
use LucianoTonet\GroqPHP\Groq;
use Twilio\Rest\Client;

class AIService
{
    private $groq;
    public function __construct($apiKey){
        $this->groq = new Groq($apiKey);
    }
    
    public function procesarMensaje($nombre, $mensaje) {
        
        $chatCompletion = $this->groq->chat()->completions()->create([
          'model'    => 'llama3-70b-8192',
          'messages' => [
            [
              'role'    => 'user',
              'content' => 'Quiero que le respondas a la persona con este nombre : ' . $nombre .'para responderle este mensaje: '. $mensaje
            ],
          ]
        ]);

        $mensajeProcesado=$chatCompletion['choices'][0]['message']['content'];  
        Log::info('mensaje procesado: '. $mensajeProcesado);
        return $mensajeProcesado;
    }

}
