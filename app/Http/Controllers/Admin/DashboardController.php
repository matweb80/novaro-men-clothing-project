<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $weekStart = now()->subDays(6)->startOfDay();

        // سفارش‌های معتبر برای محاسبه فروش
        $saleStatuses = [
            'paid',
            'preparation',
            'posted',
            'received',
        ];

        $todayOrders = Order::query()
            ->where('created_at', '>=', $today)
            ->where('created_at', '<=', now());

        $stats = [
            'orders_today' => (clone $todayOrders)->count(),

            'sales_today' => (clone $todayOrders)
                ->whereIn('status', $saleStatuses)
                ->sum('price'),

            'pending_orders' => Order::where(
                'status',
                'unpaid'
            )->count(),

            'canceled_orders' => Order::where(
                'status',
                'canceled'
            )->count(),

            'total_orders' => Order::count(),

            'total_users' => User::count(),

            'total_products' => Product::count(),

            'sales_total' => Order::query()
                ->whereIn('status', $saleStatuses)
                ->sum('price'),

            'low_stock_products' => Product::whereBetween(
                'inventory',
                [1, 5]
            )->count(),

            'out_of_stock_products' => Product::where(
                'inventory',
                '<=',
                0
            )->count(),

            'total_product_views' => Product::sum('view_count'),
        ];

        // سفارش‌های اخیر
        $recentOrders = Order::query()
            ->with('user:id,name')
            ->latest('created_at')
            ->take(8)
            ->get();

        // محصولات پرفروش
        $topProducts = Product::query()
            ->select([
                'id',
                'title',
                'slug',
                'sales_count',
                'inventory',
                'price',
                'image',
            ])
            ->orderByDesc('sales_count')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        // نمودار هفت روز اخیر
        $salesByDay = Order::query()
            ->selectRaw(
                'DATE(created_at) as sale_date,
                 SUM(price) as total_sales,
                 COUNT(*) as total_orders'
            )
            ->whereBetween('created_at', [
                $weekStart,
                now(),
            ])
            ->whereIn('status', $saleStatuses)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $salesChart = collect(range(0, 6))
            ->map(function ($offset) use ($weekStart, $salesByDay) {
                $date = $weekStart->copy()->addDays($offset);
                $key = $date->toDateString();
                $dayData = $salesByDay->get($key);

                return [
                    'date' => $key,
                    'label' => $date->format('m/d'),
                    'sales' => (int) ($dayData->total_sales ?? 0),
                    'orders' => (int) ($dayData->total_orders ?? 0),
                ];
            });

        return view('admin.admin-index', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'salesChart'
        ));
    }
}
