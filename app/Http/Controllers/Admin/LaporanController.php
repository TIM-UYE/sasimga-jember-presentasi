<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\StokLogExport;
use App\Exports\ReservasiExport;
use App\Models\Order;
use App\Models\Reservasi;
use App\Models\StokLog;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $dari   = $request->get('dari');
        $sampai = $request->get('sampai');
        $query  = StokLog::with('stok')->orderByDesc('created_at');
        if ($dari)   $query->whereDate('created_at', '>=', $dari);
        if ($sampai) $query->whereDate('created_at', '<=', $sampai);
        $stokLogs = $query->limit(200)->get();
        return view('admin.laporan.stok', compact('stokLogs', 'dari', 'sampai'));
    }

    public function pesanan(Request $request)
    {
        $dari   = $request->get('dari');
        $sampai = $request->get('sampai');
        $query  = Order::latest();
        if ($dari)   $query->whereDate('created_at', '>=', $dari);
        if ($sampai) $query->whereDate('created_at', '<=', $sampai);
        $orders = $query->limit(200)->get();
        return view('admin.laporan.pesanan', compact('orders', 'dari', 'sampai'));
    }

    public function reservasi(Request $request)
    {
        $dari   = $request->get('dari');
        $sampai = $request->get('sampai');
        $query  = Reservasi::orderByDesc('created_at');
        if ($dari)   $query->whereDate('created_at', '>=', $dari);
        if ($sampai) $query->whereDate('created_at', '<=', $sampai);
        $reservasis = $query->limit(200)->get();
        return view('admin.laporan.reservasi', compact('reservasis', 'dari', 'sampai'));
    }

    public function exportStokCsv()
    {
        return Excel::download(new StokLogExport, 'laporan_stok.csv');
    }

    public function exportStokXlsx()
    {
        return Excel::download(new StokLogExport, 'laporan_stok.xlsx');
    }

    public function exportReservasiCsv()
    {
        return Excel::download(new ReservasiExport, 'laporan_reservasi.csv');
    }

    public function exportReservasiXlsx()
    {
        return Excel::download(new ReservasiExport, 'laporan_reservasi.xlsx');
    }

    public function exportOrdersXlsx()
    {
        $orders = Order::latest()->get();
        return Excel::download(
            new class($orders) implements
                \Maatwebsite\Excel\Concerns\FromCollection,
                \Maatwebsite\Excel\Concerns\WithHeadings {
                protected $orders;
                public function __construct($orders) { $this->orders = $orders; }
                public function collection() {
                    return $this->orders->map(fn ($o) => [
                        'Kode Order' => $o->kode_order,
                        'Nama Pelanggan' => $o->nama_pelanggan,
                        'WhatsApp' => $o->nomor_hp,
                        'Total Bayar' => $o->total_bayar,
                        'Status Order' => $o->status,
                        'Status Pembayaran' => $o->payment_status,
                        'Tanggal' => $o->created_at,
                    ]);
                }
                public function headings(): array {
                    return ['Kode Order','Nama Pelanggan','WhatsApp','Total Bayar','Status Order','Status Pembayaran','Tanggal'];
                }
            },
            'laporan_orders.xlsx'
        );
    }

    public function exportOrdersCsv(): StreamedResponse
    {
        $orders   = Order::latest()->get();
        $filename = 'laporan_orders_' . now()->format('Ymd_His') . '.csv';
        return response()->stream(function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Order','Nama Pelanggan','WhatsApp','Total Bayar','Status Order','Status Pembayaran','Tanggal']);
            foreach ($orders as $o) {
                fputcsv($file, [$o->kode_order, $o->nama_pelanggan, $o->nomor_hp, $o->total_bayar, $o->status, $o->payment_status, $o->created_at]);
            }
            fclose($file);
        }, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""]);
    }
}


