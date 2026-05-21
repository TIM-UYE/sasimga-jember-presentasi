<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected ?string $groupId;
    protected string $baseUrl;
    protected int $timeout;
    protected int $retryMaxAttempts;
    protected int $retryDelayMs;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', env('FONNTE_TOKEN', ''));
        $this->groupId = config('services.fonnte.group_id', env('FONNTE_GROUP_ID', null));
        $this->baseUrl = 'https://api.fonnte.com';
        $this->timeout = 15; // seconds
        $this->retryMaxAttempts = 3;
        $this->retryDelayMs = 1000; // 1 detik antar retry
    }

    /**
     * Kirim notifikasi order delivery baru ke grup WhatsApp
     */
    public function sendNewDeliveryOrderNotification(Order $order): bool
    {
        if (!$order->isDelivery()) {
            Log::info('[WA SKIP] Order bukan delivery, skip notifikasi grup', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'metode_pengiriman' => $order->metode_pengiriman,
            ]);
            return false;
        }

        if (!$this->isConfigured()) {
            Log::warning('[WA SKIP] Token FONNTE tidak dikonfigurasi', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
            return false;
        }

        if (empty($this->groupId)) {
            Log::warning('[WA SKIP] FONNTE_GROUP_ID tidak dikonfigurasi', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
            return false;
        }

        $message = $this->formatNewDeliveryOrderMessage($order);

        Log::info('[WA SEND] Mengirim notifikasi delivery baru ke grup', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'target_group' => $this->groupId,
        ]);

        return $this->sendMessage($this->groupId, $message);
    }

    /**
     * Format pesan untuk order delivery baru
     */
    protected function formatNewDeliveryOrderMessage(Order $order): string
    {
        $itemsList = '';
        foreach ($order->items as $item) {
            $itemsList .= "- {$item->nama_menu} ({$item->qty}x)\n";
        }

        $paymentInstruction = '';
        if ($order->isCashPayment()) {
            $paymentInstruction = "💰 Driver WAJIB membayar ke restoran:\n";
            $paymentInstruction .= "Rp" . number_format($order->subtotal, 0, ',', '.') . "\n\n";
            $paymentInstruction .= "💵 Driver menagip:\n";
            $paymentInstruction .= "- harga makanan\n";
            $paymentInstruction .= "- ongkos kirim customer\n";
        } else {
            $paymentInstruction = "✅ Makanan sudah dibayar via QRIS\n\n";
            $paymentInstruction .= "💵 Driver hanya menagip ongkos kirim customer\n";
        }

        $message = "🚨 ORDER DELIVERY BARU\n\n";
        $message .= "Order ID: {$order->kode_order}\n\n";
        $message .= "Customer:\n{$order->nama_pelanggan}\n\n";
        $message .= "Pesanan:\n{$itemsList}\n";
        $message .= "Subtotal makanan:\n";
        $message .= "Rp" . number_format($order->subtotal, 0, ',', '.') . "\n\n";
        $message .= $paymentInstruction;
        $message .= "Metode pembayaran:\n" . strtoupper($order->metode_pembayaran) . "\n\n";
        $message .= "Alamat:\n{$order->alamat}\n\n";

        if ($order->catatan) {
            $message .= "Catatan:\n{$order->catatan}\n\n";
        }

        return $message;
    }

    /**
     * Kirim pesan WA dengan retry mechanism
     */
    public function sendMessage(string $target, string $message): bool
    {
        if (!$this->isConfigured()) {
            Log::error('[WA FAIL] Token FONNTE tidak dikonfigurasi', ['target' => $target]);
            return false;
        }

        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->retryMaxAttempts) {
            $attempt++;
            try {
                Log::info('[WA REQUEST] Attempt ' . $attempt . '/' . $this->retryMaxAttempts, [
                    'target' => $this->maskPhoneNumber($target),
                    'url' => $this->baseUrl . '/send',
                    'message_length' => strlen($message),
                    'timeout' => $this->timeout,
                ]);

                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Authorization' => $this->token,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])->post("{$this->baseUrl}/send", [
                        'target' => $target,
                        'message' => $message,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $status = $data['status'] ?? false;

                    Log::info('[WA SUCCESS] Pesan berhasil dikirim', [
                        'target' => $this->maskPhoneNumber($target),
                        'attempt' => $attempt,
                        'response' => $data,
                    ]);

                    // Fonnte returns status as bool/string "true" or boolean true
                    $isSent = ($status === true || $status === 'true' || $status === 1 || $status === '1');
                    return $isSent;
                }

                // Log error response
                Log::error('[WA FAIL] API error response', [
                    'target' => $this->maskPhoneNumber($target),
                    'attempt' => $attempt,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                // Don't retry on 4xx errors (invalid token, wrong target format, etc)
                if ($response->status() >= 400 && $response->status() < 500) {
                    Log::error('[WA FAIL] 4xx error, tidak akan retry', ['target' => $this->maskPhoneNumber($target)]);
                    return false;
                }

                // For 5xx or network errors, wait and retry
                if ($attempt < $this->retryMaxAttempts) {
                    $delayMs = $this->retryDelayMs * $attempt; // exponential backoff: 1s, 2s, 3s
                    Log::info('[WA RETRY] Retry dalam ' . ($delayMs / 1000) . ' detik', [
                        'target' => $this->maskPhoneNumber($target),
                        'attempt' => $attempt,
                    ]);
                    usleep($delayMs * 1000);
                }
            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('[WA FAIL] Exception saat kirim WA', [
                    'target' => $this->maskPhoneNumber($target),
                    'attempt' => $attempt,
                    'exception' => $e->getMessage(),
                    'exception_class' => get_class($e),
                ]);

                if ($attempt < $this->retryMaxAttempts) {
                    $delayMs = $this->retryDelayMs * $attempt;
                    Log::info('[WA RETRY] Retry setelah exception dalam ' . ($delayMs / 1000) . ' detik', [
                        'target' => $this->maskPhoneNumber($target),
                        'attempt' => $attempt,
                    ]);
                    usleep($delayMs * 1000);
                }
            }
        }

        // All retries exhausted
        Log::error('[WA FAIL] Semua percobaan gagal', [
            'target' => $this->maskPhoneNumber($target),
            'max_attempts' => $this->retryMaxAttempts,
            'last_exception' => $lastException ? $lastException->getMessage() : 'No exception (HTTP error)',
        ]);

        return false;
    }

    /**
     * Kirim pesan ke customer via WhatsApp
     */
    public function sendToCustomer(string $phoneNumber, string $message): bool
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        Log::info('[WA CUSTOMER] Persiapan kirim ke customer', [
            'original' => $this->maskPhoneNumber($phoneNumber),
            'formatted' => $this->maskPhoneNumber($formattedPhone),
        ]);

        if (empty($formattedPhone) || strlen($formattedPhone) < 5) {
            Log::error('[WA FAIL] Nomor HP tidak valid setelah format', [
                'original' => $this->maskPhoneNumber($phoneNumber),
                'formatted' => $this->maskPhoneNumber($formattedPhone),
            ]);
            return false;
        }

        return $this->sendMessage($formattedPhone, $message);
    }

    /**
     * Format nomor HP ke format internasional (62xxx)
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (empty($phone)) {
            Log::warning('[WA FORMAT] Nomor HP kosong setelah dibersihkan');
            return '';
        }

        // If starts with 0, replace with 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            // If doesn't start with 62, add it
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Mask nomor HP untuk logging (tampilkan 4 digit terakhir)
     */
    protected function maskPhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            return '(empty)';
        }
        $len = strlen($phone);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }
        return substr($phone, 0, -4) . '****';
    }

    /**
     * Cek apakah service sudah dikonfigurasi
     */
    public function isConfigured(): bool
    {
        return !empty($this->token) && strlen($this->token) > 10;
    }

    /**
     * Kirim notifikasi update status ke customer
     */
    public function sendOrderStatusUpdate(Order $order): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('[WA SKIP] Token tidak dikonfigurasi, skip notifikasi status', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
            return false;
        }

        if (empty($order->nomor_hp)) {
            Log::warning('[WA SKIP] Nomor HP customer kosong', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
            return false;
        }

        $statusLabels = Order::getStatusLabels();
        $status = $statusLabels[$order->status] ?? $order->status;

        $message = "📦 *Update Status Pesanan*\n\n";
        $message .= "Order ID: {$order->kode_order}\n";
        $message .= "Status: *{$status}*\n\n";

        if ($order->status === Order::STATUS_SIAP_DIAMBIL) {
            $message .= "Pesanan Anda sudah siap untuk diambil! 🎉\n";
            $message .= "Silakan datang ke restoran kami.\n";
        } elseif ($order->status === Order::STATUS_DIMASAK) {
            $message .= "Pesanan Anda sedang dimasak oleh chef kami! 👨‍🍳\n";
            $message .= "Mohon ditunggu ya.\n";
        } elseif ($order->status === Order::STATUS_DIANTAR) {
            $message .= "Pesanan Anda sedang dalam perjalanan! 🏍️\n";
            $message .= "Mohon bersiap-siap menerima pesanan.\n";
        } elseif ($order->status === Order::STATUS_SELESAI) {
            $message .= "Terima kasih telah berbelanja! 🙏\n";
            $message .= "Jangan lupa kasih rating ya! ⭐\n";
        } elseif ($order->status === Order::STATUS_DIBATALKAN) {
            $message .= "Pesanan Anda telah dibatalkan. 😔\n";
            $message .= "Hubungi kami jika ada pertanyaan.\n";
        }

        Log::info('[WA NOTIF] Mengirim notifikasi status ke customer', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'status_baru' => $order->status,
            'nomor_hp' => $this->maskPhoneNumber($order->nomor_hp),
        ]);

        return $this->sendToCustomer($order->nomor_hp, $message);
    }
}
