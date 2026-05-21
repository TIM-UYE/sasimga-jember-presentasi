<?php

namespace App\Observers;

use App\Models\Reservasi;
use App\Notifications\ReservasiStatusNotification;

class ReservasiObserver
{
    public function updated(Reservasi $reservasi): void
    {
        if ($reservasi->wasChanged('status')) {
            $reservasi->notify(new ReservasiStatusNotification($reservasi));
        }
    }
}
