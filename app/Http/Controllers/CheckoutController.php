<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuSpecialItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\WhatsAppNotificationService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('frontend.menu')
                ->with('error', 'Keranjang Anda kosong. Silakan pilih menu terlebih dahulu.');
        }

        $total = $this->calculateTotal($cart);

        return view('frontend.checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request, WhatsAppService $whatsappService, WhatsAppNotificationService $whatsappNotificationService)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
            'catatan' => 'nullable|string|max:1000',
            'metode_pengiriman' => 'required|in:pickup,delivery',

            // QRIS ONLY
            'metode_pembayaran' => 'required|in:qris',

            // Address for QRIS delivery
            'alamat_qris_manual' => 'nullable|string|max:500',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'metode_pengiriman.required' => 'Metode pengiriman wajib dipilih.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $alamatFinal = null;

        if ($validated['metode_pengiriman'] === 'delivery') {
            if (empty($validated['alamat_qris_manual'])) {
                return redirect()->back()
                    ->withErrors(['alamat_qris_manual' => 'Silakan isi alamat pengiriman Anda.'])
                    ->withInput();
            }

            $alamatFinal = $validated['alamat_qris_manual'];
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()
                ->with('error', 'Keranjang Anda kosong.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $this->calculateTotal($cart);
            $totalBayar = $subtotal;

            $order = Order::create([
                'kode_order' => Order::generateOrderCode(),
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'nomor_hp' => $validated['nomor_hp'],
                'alamat' => $alamatFinal,
                'catatan' => $validated['catatan'] ?? null,
                'metode_pengiriman' => $validated['metode_pengiriman'],

                // QRIS ONLY
                'metode_pembayaran' => 'qris',

                'subtotal' => $subtotal,
                'total_bayar' => $totalBayar,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_UNPAID,
            ]);

            foreach ($cart as $item) {
                $type = $item['type'] ?? 'menu';

                $itemType = $type === 'special'
                    ? MenuSpecialItem::class
                    : Menu::class;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $type === 'menu' ? $item['id'] : null,
                    'item_type' => $itemType,
                    'item_id' => $item['id'],
                    'nama_menu' => $item['nama'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['qty'],
                ]);
            }

            DB::commit();

            // Load items agar daftar menu terbaca lengkap oleh WhatsApp Service
            $order->load('items');

            // 1. Kirim Pesanan Baru ke WA Admin / Pemilik
            try {
                $whatsappService->sendNewOrderToAdmin($order);
            } catch (\Exception $e) {
                Log::error('Failed to send new order WA to Admin', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // 2. Kirim Notifikasi Delivery ke Group (jika delivery)
            if ($order->isDelivery()) {
                $whatsappService->sendNewDeliveryOrderNotification($order);
            }

            // 3. Kirim Pesanan ke WA Pelanggan
            try {
                $whatsappNotificationService->sendOrderCreated($order);
            } catch (\Exception $e) {
                Log::error('Failed to send order created WA notification', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            session()->forget('cart');

            return redirect()->route('payment.snap', $order->kode_order);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success($kodeOrder)
    {
        $order = Order::where('kode_order', $kodeOrder)->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
    }

    protected function calculateTotal(array $cart): float
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return $total;
    }
}
