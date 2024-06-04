<?php

namespace App\Services;

use App\Models\Cliente;

use Illuminate\Support\Facades\Log;
use LucianoTonet\GroqPHP\Groq;
use Twilio\Rest\Client;
use OpenAI;

class AIService
{
  protected $openAIClient;
  private $groq;
  public function __construct($apiKey){
      $this->groq = new Groq($apiKey);
  }
    
  public function procesarMensaje($cliente, $mensaje) {
    
    $historialMensajes = ChatService::buscarHistorialMensajes($cliente)->take(10);
    $mensajes = [];
    if($historialMensajes){
        foreach ($historialMensajes as $chat) {
            $mensajes[] = [
                'role' => 'user',
                'content' => $chat->m_recibido
            ];
            if ($chat->m_enviado) {
                $mensajes[] = [
                    'role' => 'assistant',
                    'content' => $chat->m_enviado
                ];
            }
        }
    }
    
    $mensajes[] = [
        'role' => 'user',
        'content' => $mensaje
    ];

    Log::info('chat: ' . json_encode($mensajes));

   
    $chatCompletion = $this->groq->chat()->completions()->create([
        'model' => 'llama3-70b-8192',
        'messages' => $mensajes
    ]);

   
    $mensajeProcesado = $chatCompletion['choices'][0]['message']['content'];
    Log::info('mensaje procesado: ' . $mensajeProcesado);

   
    ChatService::guardarMensaje($cliente, $mensaje, $mensajeProcesado);

    return $mensajeProcesado;
    }

}