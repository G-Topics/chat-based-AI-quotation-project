<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Producto;
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
      
      $historialMensajes = ChatService::buscarHistorialMensajes($cliente);      
      $mensajes = [];      
      foreach ($historialMensajes as $mensajeAnterior) {
          $mensajes[] = [
              'role' => $mensajeAnterior->role, 
              'content' => $mensajeAnterior->mensaje
          ];
      }      
      $mensajes[] = [
          'role' => 'user',
          'content' => $mensaje
      ];      
      Log::info('chat: ' . json_encode($mensajes));      
      $chatCompletion = $this->groq->chat()->completions()->create([
          'model'    => 'llama3-70b-8192',
          'messages' => $mensajes
      ]);
      
      $mensajeProcesado = $chatCompletion['choices'][0]['message']['content'];  
      Log::info('mensaje procesado: ' . $mensajeProcesado);     
      ChatService::guardarMensaje($cliente, 'user', $mensaje);
      ChatService::guardarMensaje($cliente, 'assistant', $mensajeProcesado);
      
      return $mensajeProcesado;
  }

  public function procesarMensajeV2($cliente, $mensajeDeCliente) {
    try {
        $esProducto = $this->interpretarConsulta($mensajeDeCliente);
        Log::info("Es producto: ".$esProducto);

        if ($esProducto) {
            return $this->responderProducto($mensajeDeCliente);
        } else {
            $historialMensajes = ChatService::buscarHistorialMensajes($cliente);      
      $mensajes = [];      
      foreach ($historialMensajes as $mensajeAnterior) {
          $mensajes[] = [
              'role' => $mensajeAnterior->role, 
              'content' => $mensajeAnterior->mensaje
          ];
      }      
      $mensajes[] = [
          'role' => 'user',
          'content' => $mensajeDeCliente
      ];      
      Log::info('chat: ' . json_encode($mensajes));      
      $chatCompletion = $this->groq->chat()->completions()->create([
          'model'    => 'llama3-70b-8192',
          'messages' => $mensajes
      ]);
      
      $mensajeProcesado = $chatCompletion['choices'][0]['message']['content'];  
      Log::info('mensaje procesado: ' . $mensajeProcesado);     
      ChatService::guardarMensaje($cliente, 'user', $mensajeDeCliente);
      ChatService::guardarMensaje($cliente, 'assistant', $mensajeProcesado);
      
      return $mensajeProcesado;
        }
    } catch (\Exception $e) {
        throw new \Exception('Erro ao fazer a solicitação: ' . $e->getMessage());
    }
}

public function interpretarConsulta($consulta)
    {
        $prompt = "La consulta que te estoy mandando es: $consulta.\nDime si se trata de una pregunta sobre productos, precios de productos o algo relacionado con la compra de algo. Responde con 'sí' o 'no'.";

        $chatCompletion = $this->groq->chat()->completions()->create([
            'model'    => 'llama3-70b-8192',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);
        $mensajeProcesado = $chatCompletion['choices'][0]['message']['content'];
        Log::info("Respuesta: ".$mensajeProcesado);

        return strtolower($mensajeProcesado) === 'sí';
    }

    protected function responderProducto($consulta)
    {
        
        $producto = $this->buscarProducto($consulta);
        Log::info("Producto encontrado: ".$producto);

        if ($producto) {
            $inventarios = $producto->inventarios;
        $totalStock = $inventarios->sum('cantidad_en_inventario');
            if ($totalStock > 0) {
                return response()->json([
                    'respuesta' => "Tenemos el producto {$producto->nombre} disponible. Descripción: {$producto->descripcion}. Precio: {$producto->precio}."
                ]);
            } else {
                return response()->json(['respuesta' => "Lo siento, el producto {$producto->name} está agotado en este momento."]);
            }
        } else {
            return response()->json(['respuesta' => 'Lo siento, no tenemos ese producto disponible.']);
        }
    }

    protected function buscarProducto($consulta)
{
    $nombreProducto = $this->extraerNombreProducto($consulta);
    Log::info("Producto extraido: " . $nombreProducto);

    if ($nombreProducto === null) {
        return null;
    } else {
        
        $nombreProducto = strtolower($nombreProducto);

        return Producto::whereRaw('LOWER(nombre) like ?', ["%{$nombreProducto}%"])->first();
    }
}

    protected function extraerNombreProducto($consulta)
{
    $palabrasClave = Producto::pluck('nombre')->map(function($value) {
        return strtolower(trim($value));
    })->toArray();
    
    $consulta = strtolower($consulta);

    Log::info("Productos: " . json_encode($palabrasClave));
    
    foreach ($palabrasClave as $palabra) {
        Log::info("Consulta: " . $consulta);
        Log::info("Palabra: " . $palabra);
        if (strpos($consulta, $palabra) !== false) {
            return $palabra;
        }
    }
    return null;
}

}
