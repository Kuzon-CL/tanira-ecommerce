<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $paymentStatus = $request->query('payment_status');
        $orderStatus = $request->query('order_status');
        $date = $request->query('date');

        $orders = Order::query()
            ->filter(
                $search !== '' ? $search : null,
                in_array($paymentStatus, array_keys(Order::PAYMENT_STATUSES), true) ? $paymentStatus : null,
                in_array($orderStatus, array_keys(Order::ORDER_STATUSES), true) ? $orderStatus : null,
                $date ?: null,
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', [
            'orders' => $orders,
            'search' => $search,
            'paymentStatus' => $paymentStatus,
            'orderStatus' => $orderStatus,
            'date' => $date,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'payments', 'user']);

        return view('admin.transactions.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return redirect()
            ->route('admin.transactions.show', $order)
            ->with('status', 'Status transaksi berhasil diperbarui.');
    }
}
