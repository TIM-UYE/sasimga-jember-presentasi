<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservasi;

$date = '2026-05-17';
$time = '21:54';

$tables = Reservasi::getAvailableTables($date, $time);
$a2 = $tables->firstWhere('posisi_row', 'A')->filter ?? null; // not correct
// Find by nama_meja or posisi
$a2 = $tables->firstWhere('nama_meja', 'Meja A2') ?? $tables->firstWhere('id', 2);

if ($a2) {
    echo "A2 is_available: " . ($a2->is_available ? 'true' : 'false') . "\n";
} else {
    echo "A2 not found in returned tables\n";
}

// Print count available
$availCount = $tables->where('is_available', true)->count();
echo "Available count: {$availCount}\n";
