<?php

namespace App\Http\Controllers;
use OpenAI\OpenAIApi;
use Illuminate\Http\Request;
use LucianoTonet\GroqPHP\Groq;

class GPTController extends Controller
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
        return $mensajeProcesado;
    }
}
