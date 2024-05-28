<?php

namespace App\Services;

use App\Models\HistorialChat;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ChatService
{
    public static  function enviarMensajePorWhatsapp($numero, $mensaje)
    {
        try {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $twilioNumber = env('TWILIO_FROM');
            $client = new Client($sid, $token);
            $client->messages->create("whatsapp:$numero", [
                'from' => "whatsapp:$twilioNumber",
                'body' => $mensaje,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar el mensaje de whatsapp: ' . $e->getMessage());
        }
    }
    public static function buscarUltimoMensaje($cliente)
    {
        $ultimoHistorial = $cliente->historiales()
            ->latest('fecha')
            ->first();
        return $ultimoHistorial;
    }
    public static function buscarHistorialMensajes($cliente) {
      
        return HistorialChat::where('id_cliente', $cliente->id)
                             ->orderBy('fecha', 'asc')
                             ->get();
    }
    public static function guardarMensaje($cliente, $role, $mensaje) {
       
        HistorialChat::create([
            'id_cliente' => $cliente->id,
            'role' => $role, 
            'mensaje' => $mensaje,
            'fecha' => now(),
        ]);
    }
}
