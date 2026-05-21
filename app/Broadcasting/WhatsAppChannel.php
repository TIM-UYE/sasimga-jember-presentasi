<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        // ambil nomor tujuan
        $target = $notifiable->no_hp ?? null;

        if (!$target) {
            return;
        }

        $token = config('services.fonnte.token');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'target' => $target,
                'message' => $message,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }
}
