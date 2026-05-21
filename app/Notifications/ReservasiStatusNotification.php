<?php

namespace App\Notifications;

use App\Models\Reservasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class ReservasiStatusNotification extends Notification
{
    use Queueable;

    protected Reservasi $reservasi;

    public function __construct(Reservasi $reservasi)
    {
        $this->reservasi = $reservasi;
    }

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): string
    {
        $nama = $this->reservasi->nama;
        $tanggal = \Carbon\Carbon::parse($this->reservasi->tanggal_reservasi)->isoFormat('D MMMM YYYY');
        $waktu = \Carbon\Carbon::parse($this->reservasi->waktu_reservasi)->format('H:i') . ' WIB';
        $jumlah = $this->reservasi->jumlah_orang;
        $status = $this->reservasi->status_label;
        $restoran = 'Sate Simpang Tiga';

        $messages = [
            'pending' => "Halo *{$nama}*,\n\n"
                . "Terima kasih telah melakukan reservasi di *{$restoran}*! 🙏\n\n"
                . "Berikut detail reservasi Anda:\n"
                . "📅 Tanggal: {$tanggal}\n"
                . "⏰ Waktu: {$waktu}\n"
                . "👥 Jumlah Orang: {$jumlah} orang\n"
                . "📌 Status: *Pending*\n\n"
                . "Reservasi Anda sedang kami proses. Mohon tunggu konfirmasi selanjutnya dari kami.\n\n"
                . "Terima kasih 🙏\n\n"
                . "— *{$restoran}*",

            'confirmed' => "Halo *{$nama}*,\n\n"
                . "✅ *Reservasi Anda TELAH DIKONFIRMASI!*\n\n"
                . "Berikut detail reservasi Anda:\n"
                . "📅 Tanggal: {$tanggal}\n"
                . "⏰ Waktu: {$waktu}\n"
                . "👥 Jumlah Orang: {$jumlah} orang\n\n"
                . "Silakan datang sesuai jadwal. Terima kasih telah memilih *{$restoran}*! 🙏\n\n"
                . "— *{$restoran}*",

            'cancelled' => "Halo *{$nama}*,\n\n"
                . "❌ *Mohon maaf, reservasi Anda DIBATALKAN.*\n\n"
                . "Berikut detail reservasi Anda:\n"
                . "📅 Tanggal: {$tanggal}\n"
                . "⏰ Waktu: {$waktu}\n"
                . "👥 Jumlah Orang: {$jumlah} orang\n\n"
                . "Silakan hubungi kami jika ada pertanyaan lebih lanjut.\n\n"
                . "Terima kasih 🙏\n\n"
                . "— *{$restoran}*",

            'completed' => "Halo *{$nama}*,\n\n"
                . "🏁 *Reservasi Anda telah SELESAI.*\n\n"
                . "Terima kasih telah menikmati hidangan di *{$restoran}*!\n"
                . "Kami harap Anda puas dengan pelayanan kami. 🙏\n\n"
                . "Sampai jumpa kembali! 😊\n\n"
                . "— *{$restoran}*",
        ];

        return $messages[$this->reservasi->status]
            ?? "Status reservasi Anda di {$restoran} telah diperbarui menjadi: *{$status}*.";
    }
}
