<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\AIService;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ChatController extends Controller
{

    protected $aiService;

    public function __construct()
    {
        $this->aiService = new AIService(env('GROQ_API_KEY'));
    }

    public function recibirMensajeDeWhatsapp(Request $request)
    {
        try {
            $requestTelefono = $request->input('From');
            $telefono = substr($requestTelefono, strpos($requestTelefono, '+'));
            $cliente = Cliente::where('telefono', $telefono)->first();
            $nombre = isset($cliente)?$cliente->nombre:'';
            $mensaje = $this->aiService->procesarMensaje($nombre, $request->input('Body'));
            ChatService::enviarMensajePorWhatsapp($telefono, $mensaje);
        } catch (\Exception $e) {
            Log::error('Error al recibir el mensaje de whatsapp: ' . $e->getMessage());
        }
    }
 
}
