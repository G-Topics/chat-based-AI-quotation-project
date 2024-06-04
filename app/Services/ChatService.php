<?php

namespace App\Services;

use App\Models\Chat;

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
        return $cliente->chats()->latest('fecha')->first();
    }
    public static function buscarHistorialMensajes($cliente)
    {
        return Chat::where('id_cliente', $cliente->id)
                    ->orderBy('fecha', 'desc')
                    ->take(10)
                    ->get()
                    ->reverse(); 
    }
    public static function guardarMensaje($cliente, $m_recibido, $m_enviado)
    {
        Chat::create([
            'id_cliente' => $cliente->id,
            'm_recibido' => $m_recibido,
            'm_enviado' => $m_enviado,
            'fecha' => now(),
        ]);
    }
}
