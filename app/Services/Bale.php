<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Bale
{
    public $token = "31422563:hJvvNVwHFvknVSZoJJSCJLuSVSNZJaQajx9HWUEr";
    public function send($text)
    {
        $url = "https://tapi.bale.ai/bot" . $this->token . "/sendMessage";
        $chat_ids = [
            "2053240814",
            "465442668",
            "273103169",
            "1720556614"
        ];

        foreach ($chat_ids as $chat_id) {
            $res = Http::get($url, [
                'chat_id' => $chat_id,
                'text' => $text
            ]);
        }
    }
}
