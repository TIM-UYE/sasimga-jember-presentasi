<?php

namespace App\Exports;

use App\Models\StokLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokLogExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return StokLog::with('stok')->get()->map(function ($item) {
            return [
                'ID' => $item->id,
                'Tipe' => $item->tipe,
                'Jumlah' => $item->jumlah,
                'Stok Sebelum' => $item->stok_sebelum,
                'Stok Sesudah' => $item->stok_sesudah,
                'Keterangan' => $item->keterangan,
                'Tanggal' => $item->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tipe',
            'Jumlah',
            'Stok Sebelum',
            'Stok Sesudah',
            'Keterangan',
            'Tanggal',
        ];
    }
}