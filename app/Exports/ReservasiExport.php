<?php

namespace App\Exports;

use App\Models\Reservasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservasiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Reservasi::all()->map(function ($item) {

            return [
                'ID' => $item->id,
                'Nama' => $item->nama,
                'Nomor WA' => $item->formatted_wa,
                'Tanggal Reservasi' => $item->tanggal_reservasi->format('d-m-Y'),
                'Waktu' => $item->waktu_reservasi,
                'Jumlah Orang' => $item->jumlah_orang,
                'Status' => $item->status_label,
                'Dibuat' => $item->created_at->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Nomor WA',
            'Tanggal Reservasi',
            'Waktu',
            'Jumlah Orang',
            'Status',
            'Dibuat',
        ];
    }
}