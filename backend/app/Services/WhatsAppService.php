<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $token;
    protected $phoneId;

    public function __construct()
    {
        // Se leen las variables del archivo .env
        $this->token = env('WHATSAPP_TOKEN');
        $this->phoneId = env('WHATSAPP_PHONE_ID');
    }

    public function enviarMensaje($numero, $mensaje)
    {
        // URL de la API oficial de WhatsApp
        $url = "https://graph.facebook.com/v17.0/{$this->phoneId}/messages";

        // Se hace la request HTTP POST a la API
        $response = Http::withToken($this->token)->post($url, [
            "messaging_product" => "whatsapp",   // siempre "whatsapp"
            "to" => $numero,                     // número destino (E.164: ej. 5493512345678)
            "type" => "text",                    // tipo de mensaje (puede ser text, image, template, etc.)
            "text" => ["body" => $mensaje]       // cuerpo del mensaje
        ]);

        return $response->json(); // devuelve la respuesta de la API
    }
}
