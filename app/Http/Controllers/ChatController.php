<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ChatController extends Controller
{
    public function recibirMensajeDeWhatsapp(Request $request)
    {
        try {
            Log::info('body: ' . $request->input('Body'));
            Log::info('from: ' . $request->input('From'));
            $requestTelefono = $request->input('From');
            $telefono = substr($requestTelefono, strpos($requestTelefono, '+'));
            $cliente = Cliente::where('telefono', $telefono)->first();
            Log::info('cliente: ' . $cliente);
            if (!$cliente) {
                $mensaje= 'Hola, como estas?';
            }
            else {
                $mensaje = 'Hola ' .$cliente->nombre . ', como estas?';
            }
            self::enviarMensajePorWhatsapp($telefono, $mensaje);
        } catch (\Exception $e) {
            Log::error('Error al recibir el mensaje de whatsapp: ' . $e->getMessage());
        }
    }
    public  function enviarMensajePorWhatsapp($numero, $mensaje)
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
}
