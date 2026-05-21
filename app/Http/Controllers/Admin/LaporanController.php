<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StokLogExport;
use App\Exports\ReservasiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EXPORT STOK
    |--------------------------------------------------------------------------
    */

    public function exportStokCsv()
    {
        return Excel::download(
            new StokLogExport,
            'laporan_stok.csv'
        );
    }

    public function exportStokXlsx()
    {
        return Excel::download(
            new StokLogExport,
            'laporan_stok.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT RESERVASI
    |--------------------------------------------------------------------------
    */

    public function exportReservasiCsv()
    {
        return Excel::download(
            new ReservasiExport,
            'laporan_reservasi.csv'
        );
    }

    public function exportReservasiXlsx()
    {
        return Excel::download(
            new ReservasiExport,
            'laporan_reservasi.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT ORDERS EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportOrdersXlsx()
    {
        $orders = Order::latest()->get();

        return Excel::download(
            new class($orders) implements
                \Maatwebsite\Excel\Concerns\FromCollection,
                \Maatwebsite\Excel\Concerns\WithHeadings {

                protected $orders;

                public function __construct($orders)
                {
                    $this->orders = $orders;
                }

                public function collection()
                {
                    return $this->orders->map(function ($order) {
                        return [
                            'Kode Order' => $order->kode_order,
                            'Nama Pelanggan' => $order->nama_pelanggan,
                            'WhatsApp' => $order->nomor_hp,
                            'Total Bayar' => $order->total_bayar,
                            'Status Order' => $order->status,
                            'Status Pembayaran' => $order->payment_status,
                            'Tanggal' => $order->created_at,
                        ];
                    });
                }

                public function headings(): array
                {
                    return [
                        'Kode Order',
                        'Nama Pelanggan',
                        'WhatsApp',
                        'Total Bayar',
                        'Status Order',
                        'Status Pembayaran',
                        'Tanggal',
                    ];
                }
            },
            'laporan_orders.xlsx'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT ORDERS CSV
    |--------------------------------------------------------------------------
    */

    public function exportOrdersCsv(): StreamedResponse
    {
        $orders = Order::latest()->get();

        $filename = 'laporan_orders_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Kode Order',
                'Nama Pelanggan',
                'WhatsApp',
                'Total Bayar',
                'Status Order',
                'Status Pembayaran',
                'Tanggal',
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->kode_order,
                    $order->nama_pelanggan,
                    $order->nomor_hp,
                    $order->total_bayar,
                    $order->status,
                    $order->payment_status,
                    $order->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}