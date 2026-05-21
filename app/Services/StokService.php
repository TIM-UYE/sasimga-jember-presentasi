<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuSpecialItem;
use App\Models\Order;
use App\Models\StokLog;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StokService
{
    public function kurangiStokUntukOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::with([
                    'items.menu.komposisiBahan.stok',
                    'items.itemable.komposisiBahan.stok',
                ])
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($order->stok_dikurangi_at !== null) {
                Log::info('[STOK SKIP] Stok sudah pernah dikurangi untuk order ini', [
                    'order_id' => $order->id,
                    'kode_order' => $order->kode_order,
                    'stok_dikurangi_at' => $order->stok_dikurangi_at,
                ]);

                return;
            }

            if ($order->payment_status !== Order::PAYMENT_PAID) {
                Log::info('[STOK SKIP] Payment belum paid, stok tidak dikurangi', [
                    'order_id' => $order->id,
                    'kode_order' => $order->kode_order,
                    'payment_status' => $order->payment_status,
                ]);

                return;
            }

            foreach ($order->items as $item) {
                $produk = $this->resolveProduk($item);

                if (!$produk) {
                    Log::warning('[STOK SKIP ITEM] Produk tidak ditemukan untuk order item', [
                        'order_item_id' => $item->id,
                        'menu_id' => $item->menu_id,
                        'item_type' => $item->item_type,
                        'item_id' => $item->item_id,
                        'nama_menu' => $item->nama_menu,
                    ]);

                    continue;
                }

                if (!method_exists($produk, 'komposisiBahan')) {
                    Log::warning('[STOK SKIP ITEM] Produk belum punya relasi komposisiBahan', [
                        'order_item_id' => $item->id,
                        'produk_class' => get_class($produk),
                        'produk_id' => $produk->id,
                        'nama_menu' => $item->nama_menu,
                    ]);

                    continue;
                }

                $komposisiBahan = $produk->komposisiBahan;

                if ($komposisiBahan->isEmpty()) {
                    Log::warning('[STOK SKIP ITEM] Produk belum memiliki komposisi bahan', [
                        'order_item_id' => $item->id,
                        'produk_class' => get_class($produk),
                        'produk_id' => $produk->id,
                        'nama_menu' => $item->nama_menu,
                    ]);

                    continue;
                }

                foreach ($komposisiBahan as $komposisi) {
                    $stok = $komposisi->stok;

                    if (!$stok) {
                        Log::warning('[STOK SKIP BAHAN] Data stok tidak ditemukan', [
                            'menu_bahan_id' => $komposisi->id,
                            'stok_id' => $komposisi->stok_id,
                        ]);

                        continue;
                    }

                    $jumlahKeluar = (float) $komposisi->jumlah_dibutuhkan * (int) $item->qty;
                    $stokSebelum = (float) $stok->jumlah_stok;
                    $stokSesudah = $stokSebelum - $jumlahKeluar;

                    if ($stokSesudah < 0) {
                        throw new Exception(
                            'Stok bahan "' . $stok->nama_bahan . '" tidak mencukupi. ' .
                            'Stok tersedia: ' . $stokSebelum . ' ' . $stok->satuan . ', ' .
                            'dibutuhkan: ' . $jumlahKeluar . ' ' . $stok->satuan . '.'
                        );
                    }

                    $stok->update([
                        'jumlah_stok' => $stokSesudah,
                    ]);

                    StokLog::create([
                        'stok_id' => $stok->id,
                        'tipe' => 'keluar',
                        'jumlah' => $jumlahKeluar,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'keterangan' => 'Pesanan ' . $order->kode_order . ' - ' . $item->nama_menu . ' x ' . $item->qty,
                        'referensi_id' => $order->id,
                        'referensi_type' => Order::class,
                    ]);
                }
            }

            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'stok_dikurangi_at' => now(),
                    'updated_at' => now(),
                ]);

            Log::info('[STOK SUCCESS] Stok berhasil dikurangi untuk order', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
        });
    }

    protected function resolveProduk($item)
    {
        if ($item->item_type && $item->item_id && $item->itemable) {
            return $item->itemable;
        }

        if ($item->menu) {
            return $item->menu;
        }

        if ($item->menu_id) {
            return Menu::find($item->menu_id);
        }

        return null;
    }
}