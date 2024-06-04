<?php

namespace App\Services;

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
    try {
    $esProducto = $this->interpretarConsulta($mensaje);
    Log::info("Es producto: ".$esProducto);

    if ($esProducto) {
      return $this->responderProducto($mensaje);
    } else {
    
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
        'content' => "Esta es la consulta del cliente, $mensaje.\nResponde como un vendedor de productos agroquimicos"
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
    } catch (\Exception $e) {
        throw new \Exception('Error al hacer la solicitud: ' . $e->getMessage());
    }
  }

  public function interpretarConsulta($consulta)
  {
      $prompt = "La consulta que te estoy mandando es: $consulta.\nDime si se trata de una pregunta sobre productos agroquimicos, precios de productos o algo relacionado con la compra de algun  producto agroquimico. Responde con 'sí' o 'no'.";

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
         // $inventarios = $producto->inventarios;
     // $totalStock = $inventarios->sum('cantidad_en_inventario');
         // if ($totalStock > 0) {
              return 
                  "Tenemos el producto {$producto->nombre} disponible. Descripción: {$producto->descripcion}. su Precio: {$producto->precio}.";
              
         // } else {
             // return response()->json(['respuesta' => "Lo siento, el producto {$producto->name} está agotado en este momento."]);
         // }
      } else {
        return  'Lo siento, no tenemos ese producto disponible.';
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
      
      if (strpos($consulta, $palabra) !== false) {
          return $palabra;
      }
  }
  return null;
}

}
