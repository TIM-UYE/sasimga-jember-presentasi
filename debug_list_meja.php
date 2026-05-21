<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Meja;

$mejas = Meja::orderBy('id')->get();
foreach ($mejas as $m) {
    echo "id={$m->id} nama_meja='{$m->nama_meja}' posisi_row='{$m->posisi_row}' posisi_col='{$m->posisi_col}' kapasitas={$m->kapasitas}\n";
}
