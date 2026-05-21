<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KursiReservasi;

$date = '2026-05-17';
$mejaId = 2;

$kursis = KursiReservasi::where('meja_id', $mejaId)->where('tanggal', $date)->orderBy('waktu_sesi')->get();

echo "Raw KursiReservasi for meja_id={$mejaId} on {$date}:\n";
foreach ($kursis as $k) {
    echo "- id={$k->id} tanggal='" . $k->tanggal . "' waktu_sesi='" . $k->waktu_sesi . "' tersedia={$k->tersedia} reservasi_id={$k->reservasi_id} created_at={$k->created_at}\n";
}
