<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = DB::selectOne('
            SELECT
                (SELECT COUNT(*) FROM users WHERE role = ?) AS customers,
                (SELECT COUNT(*) FROM orders) AS orders,
                (SELECT COUNT(*) FROM products) AS products
        ', [User::ROLE_CUSTOMER]);

        $pendingPayment = Order::query()
            ->whereIn('payment_status', ['pending', 'unpaid'])
            ->whereNotIn('order_status', ['cancelled', 'completed'])
            ->count();

        $latestOrders = Order::query()
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'customerCount' => (int) $stats->customers,
            'orderCount' => (int) $stats->orders,
            'productCount' => (int) $stats->products,
            'pendingPayment' => $pendingPayment,
            'latestOrders' => $latestOrders,
        ]);
    }
}
