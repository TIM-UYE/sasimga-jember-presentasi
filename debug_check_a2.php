<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Meja;
use App\Models\KursiReservasi;
use App\Models\Reservasi;
use Carbon\Carbon;

$date = '2026-05-17';
$mejaName = 'A2';

$meja = Meja::where('nama_meja', $mejaName)->first();
if (! $meja) {
    echo "Meja {$mejaName} not found\n";
    exit(0);
}

echo "Meja id: {$meja->id}, nama: {$meja->nama_meja}, kapasitas: {$meja->kapasitas}\n\n";

$kursis = KursiReservasi::where('meja_id', $meja->id)->where('tanggal', $date)->orderBy('waktu_sesi')->get();

echo "KursiReservasi entries for {$date}:\n";
foreach ($kursis as $k) {
    $dt = Carbon::parse($k->tanggal . ' ' . $k->waktu_sesi);
    echo "- id={$k->id} waktu_sesi={$k->waktu_sesi} tersedia={$k->tersedia} reservasi_id={$k->reservasi_id} created_at={$k->created_at} datetime={$dt->toDateTimeString()}\n";
}

$reservasis = Reservasi::where('tanggal_reservasi', $date)->orderBy('waktu_reservasi')->get();

echo "\nReservasi on {$date}:\n";
foreach ($reservasis as $r) {
    echo "- id={$r->id} waktu_reservasi={$r->waktu_reservasi} meja_ids=" . json_encode($r->meja_ids) . " status={$r->status} created_at={$r->created_at}\n";
}

// Check getAvailableTables for a few times around 21:54
$times = ['21:37','21:43','21:44','21:53','21:54','21:55','21:37:00','09:54 PM'];

foreach ($times as $t) {
    $tables = Reservasi::getAvailableTables($date, $t);
    $a = $tables->where('nama_meja', $mejaName)->first();
    $avail = $a ? ($a->is_available ? 'available' : 'booked') : 'no-data';
    echo "\ngetAvailableTables({$date}, {$t}) => A2 is {$avail}\n";
}
