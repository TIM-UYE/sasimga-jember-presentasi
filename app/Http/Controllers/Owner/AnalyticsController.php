<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservasi;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the owner analytics dashboard.
     */
    public function index()
    {
        // Total revenue (from completed/paid orders)
        $totalRevenue = Order::whereIn('status', ['selesai', 'paid'])
            ->orWhere(function($q) {
                $q->where('payment_status', 'paid');
            })
            ->sum('total_bayar');

        // Total transactions
        $totalTransactions = Order::count();

        // Total reservations
        $totalReservations = Reservasi::count();

        // Total customers (users with role 'user')
        $totalCustomers = User::where('role', 'user')->count();

        // Total menus
        $totalMenus = Menu::count();

        // Revenue today
        $revenueToday = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->whereDate('created_at', today())
            ->sum('total_bayar');

        // Revenue this month
        $revenueMonth = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_bayar');

        // Transactions today
        $transactionsToday = Order::whereDate('created_at', today())->count();

        // Pending reservations
        $pendingReservations = Reservasi::where('status', 'pending')->count();

        // Daily sales chart (last 7 days)
        $dailySales = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_bayar) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Weekly sales chart (last 4 weeks)
        $weeklySales = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->where('created_at', '>=', now()->subWeeks(4))
            ->select(
                DB::raw('YEARWEEK(created_at, 1) as week'),
                DB::raw('SUM(total_bayar) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Monthly sales chart (last 6 months)
        $monthlySales = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total_bayar) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Yearly sales chart (last 5 years)
        $yearlySales = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_bayar) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->take(5)
            ->get();

        // Top selling menus
        $topSellingMenus = OrderItem::select(
                'menu_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->with('menu')
            ->get();

        // Recent orders
        $recentOrders = Order::latest()->take(5)->get();

        // Monthly revenue trend for current year
        $monthlyRevenue = Order::where(function($q) {
                $q->whereIn('status', ['selesai'])
                  ->orWhere('payment_status', 'paid');
            })
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Fill in missing months with 0
        $monthlyRevenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenueData[$i] = $monthlyRevenue->has($i) ? (int) $monthlyRevenue[$i]->total : 0;
        }

        return view('owner.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'totalReservations',
            'totalCustomers',
            'totalMenus',
            'revenueToday',
            'revenueMonth',
            'transactionsToday',
            'pendingReservations',
            'dailySales',
            'weeklySales',
            'monthlySales',
            'yearlySales',
            'topSellingMenus',
            'recentOrders',
            'monthlyRevenueData'
        ));
    }
}
