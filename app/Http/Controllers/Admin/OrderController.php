<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\StokService;
use App\Services\WhatsAppNotificationService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $paymentStatus = $request->get('payment_status', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 15);

        $query = Order::with('items')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($perPage)->withQueryString();

        $statusLabels = Order::getStatusLabels();
        $paymentStatusLabels = Order::getPaymentStatusLabels();
        $deliveryMethodLabels = Order::getDeliveryMethodLabels();
        $paymentMethodLabels = Order::getPaymentMethodLabels();

        return view('admin.orders.index', compact(
            'orders',
            'status',
            'paymentStatus',
            'search',
            'statusLabels',
            'paymentStatusLabels',
            'deliveryMethodLabels',
            'paymentMethodLabels'
        ));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['items', 'paymentTransactions']);

        $statusLabels = Order::getStatusLabels();
        $paymentStatusLabels = Order::getPaymentStatusLabels();
        $deliveryMethodLabels = Order::getDeliveryMethodLabels();
        $paymentMethodLabels = Order::getPaymentMethodLabels();

        return view('admin.orders.show', compact(
            'order',
            'statusLabels',
            'paymentStatusLabels',
            'deliveryMethodLabels',
            'paymentMethodLabels'
        ));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order, WhatsAppService $whatsappService, StokService $stokService)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,dimasak,siap_diambil,diantar,selesai,dibatalkan',
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $order->status;

        Log::info('[DEBUG STOK] updateStatus terpanggil', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'status_lama' => $oldStatus,
            'status_baru' => $newStatus,
            'payment_status' => $order->payment_status,
            'request' => $request->all(),
        ]);

        if ($newStatus === $oldStatus) {
            Log::warning('[ORDER DUPLICATE] Status sama, tolak request', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'status' => $oldStatus,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pesanan sudah "' . ($order->getStatusLabels()[$newStatus] ?? $newStatus) . '". Tidak ada perubahan.',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Status pesanan sudah "' . ($order->getStatusLabels()[$newStatus] ?? $newStatus) . '". Tidak ada perubahan.');
        }

        if (!$order->canChangeToStatus($newStatus)) {
            Log::warning('[ORDER INVALID] Perubahan status tidak diizinkan', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perubahan status tidak diizinkan.',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Perubahan status tidak diizinkan.');
        }

        try {
            DB::beginTransaction();

            $order->status = $newStatus;
            $order->save();

            if ($newStatus === Order::STATUS_SELESAI && $order->payment_status === Order::PAYMENT_UNPAID) {
                $order->payment_status = Order::PAYMENT_PAID;
                $order->save();

                Log::info('[ORDER PAYMENT] Pembayaran otomatis di-set ke paid karena status selesai', [
                    'order_id' => $order->id,
                    'kode_order' => $order->kode_order,
                ]);
            }

            if ($newStatus === Order::STATUS_DIBATALKAN) {
                $order->payment_status = Order::PAYMENT_UNPAID;
                $order->save();

                Log::info('[ORDER CANCEL] Pembayaran dikembalikan ke unpaid karena dibatalkan', [
                    'order_id' => $order->id,
                    'kode_order' => $order->kode_order,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $newStatus,
                'previous_status' => $oldStatus,
                'changed_by' => auth()->id(),
                'metadata' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'changed_from' => 'admin_panel',
                ],
            ]);

            DB::commit();

            $freshOrder = $order->fresh();

            if ($newStatus === Order::STATUS_SELESAI && $freshOrder->payment_status === Order::PAYMENT_PAID) {
                $stokService->kurangiStokUntukOrder($freshOrder);
            }

            Log::info('[ORDER SUCCESS] Status berhasil diubah', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'payment_status' => $freshOrder->payment_status,
                'stok_dikurangi_at' => $freshOrder->stok_dikurangi_at,
                'changed_by' => auth()->id(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ORDER DB/STOK FAIL] Gagal menyimpan perubahan status atau mengurangi stok', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan status: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menyimpan perubahan status: ' . $e->getMessage());
        }

        $waStatuses = [
            Order::STATUS_DIMASAK,
            Order::STATUS_SIAP_DIAMBIL,
            Order::STATUS_DIANTAR,
            Order::STATUS_SELESAI,
            Order::STATUS_DIBATALKAN,
        ];

        if (in_array($newStatus, $waStatuses)) {
            try {
                $waResult = $whatsappService->sendOrderStatusUpdate($order->fresh());

                if ($waResult) {
                    Log::info('[ORDER WA SUCCESS] Notifikasi WA berhasil dikirim', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                        'status' => $newStatus,
                    ]);
                } else {
                    Log::warning('[ORDER WA FAIL] Notifikasi WA gagal dikirim', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                        'status' => $newStatus,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('[ORDER WA EXCEPTION] Exception saat kirim notifikasi WA', [
                    'order_id' => $order->id,
                    'kode_order' => $order->kode_order,
                    'status' => $newStatus,
                    'exception' => $e->getMessage(),
                ]);
            }
        } else {
            Log::info('[ORDER WA SKIP] Status tidak termasuk dalam daftar kirim WA', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'status' => $newStatus,
                'wa_statuses' => $waStatuses,
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            $freshOrder = $order->fresh();

            $statusLabels = Order::getStatusLabels();
            $paymentStatusLabels = Order::getPaymentStatusLabels();
            $nextStatus = $freshOrder->getNextStatus();
            $statusFlow = $freshOrder->getStatusFlow();
            $currentIndex = array_search($freshOrder->status, array_keys($statusFlow));

            $orderData = [
                'id' => $freshOrder->id,
                'kode_order' => $freshOrder->kode_order,
                'nama_pelanggan' => $freshOrder->nama_pelanggan,
                'nomor_hp' => $freshOrder->nomor_hp,
                'metode_pengiriman' => $freshOrder->metode_pengiriman,
                'metode_pembayaran' => $freshOrder->metode_pembayaran,
                'total_bayar' => number_format($freshOrder->total_bayar, 0, ',', '.'),
                'total_bayar_raw' => (float) $freshOrder->total_bayar,
                'status' => $freshOrder->status,
                'payment_status' => $freshOrder->payment_status,
                'status_label' => $statusLabels[$freshOrder->status] ?? $freshOrder->status,
                'payment_status_label' => $paymentStatusLabels[$freshOrder->payment_status] ?? $freshOrder->payment_status,
                'status_color' => $freshOrder->getStatusColor(),
                'status_icon' => $freshOrder->getStatusIcon(),
                'next_status' => $nextStatus,
                'next_status_label' => $nextStatus ? ($statusLabels[$nextStatus] ?? $nextStatus) : null,
                'is_active' => $freshOrder->isActive(),
                'created_at' => $freshOrder->created_at->format('d M Y, H:i'),
                'detail_url' => route('admin.orders.show', $freshOrder),
                'status_flow' => $statusFlow,
                'current_index' => $currentIndex,
                'flow_keys' => array_keys($statusFlow),
            ];

            $stats = [
                'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
                'diproses' => Order::where('status', Order::STATUS_DIPROSES)->count(),
                'dimasak' => Order::where('status', Order::STATUS_DIMASAK)->count(),
                'siap_diambil' => Order::where('status', Order::STATUS_SIAP_DIAMBIL)->count(),
                'selesai' => Order::where('status', Order::STATUS_SELESAI)->count(),
                'dibatalkan' => Order::where('status', Order::STATUS_DIBATALKAN)->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diubah!',
                'order' => $orderData,
                'stats' => $stats,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diubah!');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order, WhatsAppNotificationService $whatsappNotificationService, StokService $stokService)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        Log::info('[DEBUG STOK] updatePaymentStatus terpanggil', [
            'order_id' => $order->id,
            'kode_order' => $order->kode_order,
            'payment_status_lama' => $order->payment_status,
            'request' => $request->all(),
        ]);

        $oldPaymentStatus = $order->payment_status;

        try {
            $order->payment_status = $validated['payment_status'];

            if ($validated['payment_status'] === Order::PAYMENT_PAID && $order->isQRISPayment() && $order->status === Order::STATUS_PENDING) {
                $order->status = Order::STATUS_DIPROSES;
            }

            $order->save();

            if ($oldPaymentStatus === Order::PAYMENT_UNPAID
                && $validated['payment_status'] === Order::PAYMENT_PAID
            ) {
                $stokService->kurangiStokUntukOrder($order->fresh());
            }

            Log::info('[ORDER PAYMENT] Status pembayaran diubah', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'payment_status_lama' => $oldPaymentStatus,
                'payment_status_baru' => $validated['payment_status'],
                'stok_dikurangi_at' => $order->fresh()->stok_dikurangi_at,
                'changed_by' => auth()->id(),
            ]);

            if ($oldPaymentStatus === Order::PAYMENT_UNPAID
                && $validated['payment_status'] === Order::PAYMENT_PAID
                && $order->isQRISPayment()
            ) {
                try {
                    $whatsappNotificationService->sendPaymentSuccess($order->fresh());

                    Log::info('[ORDER PAYMENT WA] Notifikasi pembayaran QRIS terkirim', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                        'nomor_hp' => $order->nomor_hp,
                    ]);
                } catch (\Exception $e) {
                    Log::error('[ORDER PAYMENT WA FAIL] Gagal mengirim notifikasi WA', [
                        'order_id' => $order->id,
                        'kode_order' => $order->kode_order,
                        'exception' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('[ORDER PAYMENT/STOK FAIL] Gagal update status pembayaran atau mengurangi stok', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah status pembayaran: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Status pembayaran berhasil diubah!');
    }

    /**
     * AJAX Polling endpoint
     */
    public function pollData(Request $request)
    {
        $status = $request->get('status', 'all');
        $paymentStatus = $request->get('payment_status', 'all');
        $search = $request->get('search', '');

        $query = Order::with('items')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        $orders = $query->take(50)->get();

        $statusLabels = Order::getStatusLabels();
        $paymentStatusLabels = Order::getPaymentStatusLabels();

        $ordersData = $orders->map(function ($order) use ($statusLabels, $paymentStatusLabels) {
            $nextStatus = $order->getNextStatus();
            $statusFlow = $order->getStatusFlow();
            $currentIndex = array_search($order->status, array_keys($statusFlow));

            return [
                'id' => $order->id,
                'kode_order' => $order->kode_order,
                'nama_pelanggan' => $order->nama_pelanggan,
                'nomor_hp' => $order->nomor_hp,
                'metode_pengiriman' => $order->metode_pengiriman,
                'metode_pembayaran' => $order->metode_pembayaran,
                'total_bayar' => number_format($order->total_bayar, 0, ',', '.'),
                'total_bayar_raw' => (float) $order->total_bayar,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'status_label' => $statusLabels[$order->status] ?? $order->status,
                'payment_status_label' => $paymentStatusLabels[$order->payment_status] ?? $order->payment_status,
                'status_color' => $order->getStatusColor(),
                'status_icon' => $order->getStatusIcon(),
                'next_status' => $nextStatus,
                'next_status_label' => $nextStatus ? ($statusLabels[$nextStatus] ?? $nextStatus) : null,
                'is_active' => $order->isActive(),
                'created_at' => $order->created_at->format('d M Y, H:i'),
                'detail_url' => route('admin.orders.show', $order),
                'status_flow' => $statusFlow,
                'current_index' => $currentIndex,
                'flow_keys' => array_keys($statusFlow),
            ];
        });

        $stats = [
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'diproses' => Order::where('status', Order::STATUS_DIPROSES)->count(),
            'dimasak' => Order::where('status', Order::STATUS_DIMASAK)->count(),
            'siap_diambil' => Order::where('status', Order::STATUS_SIAP_DIAMBIL)->count(),
            'selesai' => Order::where('status', Order::STATUS_SELESAI)->count(),
            'dibatalkan' => Order::where('status', Order::STATUS_DIBATALKAN)->count(),
        ];

        return response()->json([
            'success' => true,
            'orders' => $ordersData,
            'stats' => $stats,
        ]);
    }

    /**
     * Get order statistics for dashboard
     */
    public function stats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::whereIn('status', [
                Order::STATUS_DIPROSES,
                Order::STATUS_DIMASAK,
                Order::STATUS_SIAP_DIAMBIL,
            ])->count(),
            'completed_orders' => Order::where('status', Order::STATUS_SELESAI)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_DIBATALKAN)->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                ->where('status', Order::STATUS_SELESAI)
                ->sum('total_bayar'),
        ];

        return response()->json($stats);
    }

    /**
     * Destroy an order
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            $previousStatus = $order->status;

            $order->status = Order::STATUS_DIBATALKAN;
            $order->payment_status = Order::PAYMENT_UNPAID;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => Order::STATUS_DIBATALKAN,
                'previous_status' => $previousStatus,
                'changed_by' => auth()->id(),
                'metadata' => [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'changed_from' => 'admin_destroy',
                ],
            ]);

            DB::commit();

            Log::info('[ORDER DESTROY] Pesanan dibatalkan via destroy', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ORDER DESTROY FAIL] Gagal membatalkan pesanan', [
                'order_id' => $order->id,
                'kode_order' => $order->kode_order,
                'exception' => $e->getMessage(),
            ]);

            return redirect()->route('admin.orders.index')
                ->with('error', 'Gagal membatalkan pesanan.');
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan!');
    }
}