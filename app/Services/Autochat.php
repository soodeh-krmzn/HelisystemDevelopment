<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class Autochat
{
    public $token = "1bb306ZMLJ1MdKB8vS43IrAFULzjOv8E6cBUFSkrbausNJMPovI0341kVjJorM7t";

    public function send($mobile, $text)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('https://api.autochat.ir/api/v1/whatsapp/send-message', [
            'token' => $this->token,
            'to' => $mobile,
            'message' => $text
        ]);
        if ($response->successful()) {
            echo $response->body();
        } else {
            echo 'Unexpected HTTP status: ' . $response->status() . ' ' .
                $response->reason();
        }
    }
}
