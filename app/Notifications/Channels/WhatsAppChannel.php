<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // Ambil pesan dari notification
        $message = $notification->toWhatsApp($notifiable);

        // Ambil nomor WA (sudah diformat)
        $recipientNumber = $notifiable->formatted_wa;

        Log::info('WA FINAL: ' . $recipientNumber);

        if (empty($recipientNumber)) {
            Log::warning('WhatsApp skipped: nomor kosong untuk ' . ($notifiable->nama ?? 'unknown'));
            return;
        }

        // Ambil token dari config
        $apiToken = config('services.fonnte.token');

        if (empty($apiToken)) {
            Log::warning('WhatsApp skipped: FONNTE_TOKEN belum diset');
            return;
        }

        try {
            $response = Http::withOptions([
                // 🔥 FIX SSL ERROR (Laragon / Windows)
                'verify' => false,
            ])->withHeaders([
                'Authorization' => $apiToken,
            ])->post('https://api.fonnte.com/send', [
                'target' => $recipientNumber,
                'message' => $message,
            ]);

            // 🔍 Log response
            Log::info('FONNTE RESPONSE: ' . $response->body());

            if ($response->successful()) {
                $body = $response->json();

                if (($body['status'] ?? false) === true) {
                    Log::info("WA BERHASIL ke {$recipientNumber} (Reservasi ID: {$notifiable->id})");
                } else {
                    Log::warning("WA GAGAL ke {$recipientNumber}: " . ($body['reason'] ?? 'unknown'));
                }
            } else {
                Log::error("HTTP ERROR ke {$recipientNumber}: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("EXCEPTION WA ke {$recipientNumber}: " . $e->getMessage());
        }
    }
}
