<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KursiReservasi;
use App\Models\Reservasi;
use Carbon\Carbon;

$date = '2026-05-17';
$mejaId = 2;

$kursis = KursiReservasi::where('meja_id', $mejaId)->where('tanggal', $date)->orderBy('waktu_sesi')->get();

echo "KursiReservasi for meja_id={$mejaId} on {$date}:\n";
foreach ($kursis as $k) {
    $dt = Carbon::parse($k->tanggal . ' ' . $k->waktu_sesi);
    echo "- id={$k->id} waktu_sesi={$k->waktu_sesi} tersedia={$k->tersedia} reservasi_id={$k->reservasi_id} created_at={$k->created_at} datetime={$dt->toDateTimeString()}\n";
}

$reservasis = Reservasi::where('tanggal_reservasi', $date)->orderBy('waktu_reservasi')->get();

echo "\nReservasi list for {$date}:\n";
foreach ($reservasis as $r) {
    echo "- id={$r->id} waktu_reservasi={$r->waktu_reservasi} meja_ids=" . json_encode($r->meja_ids) . " status={$r->status} created_at={$r->created_at}\n";
}
