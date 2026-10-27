@extends('layouts.admin')

@section('title', 'Transaksi')

@php
    $paymentTone = fn (?string $s) => match ($s) {
        'paid' => 'success',
        'pending' => 'warning',
        'failed', 'refunded' => 'danger',
        default => 'neutral',
    };
    $orderTone = fn (?string $s) => match ($s) {
        'completed' => 'success',
        'processing', 'shipped' => 'info',
        'cancelled' => 'danger',
        default => 'neutral',
    };
@endphp

@section('content')
    <x-admin.page-header title="Transaksi" description="Pantau dan verifikasi transaksi pelanggan." />

    <form method="GET" action="{{ route('admin.transactions.index') }}" class="admin-card mb-5 p-4">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <label for="q" class="admin-label">Cari</label>
                <input type="search" name="q" id="q" value="{{ $search }}" placeholder="No. transaksi / pelanggan" class="admin-input">
            </div>
            <div>
                <label for="payment_status" class="admin-label">Status Pembayaran</label>
                <select name="payment_status" id="payment_status" class="admin-input">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($paymentStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="order_status" class="admin-label">Status Pesanan</label>
                <select name="order_status" id="order_status" class="admin-input">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Order::ORDER_STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($orderStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="admin-label">Tanggal</label>
                <div class="flex gap-2">
                    <input type="date" name="date" id="date" value="{{ $date }}" class="admin-input">
                    <button type="submit" class="admin-btn admin-btn-primary shrink-0">Filter</button>
                </div>
            </div>
        </div>
        @if ($search !== '' || $paymentStatus || $orderStatus || $date)
            <div class="mt-3">
                <a href="{{ route('admin.transactions.index') }}" class="text-xs font-semibold text-caramel hover:opacity-75">Reset filter</a>
            </div>
        @endif
    </form>

    <div class="admin-card overflow-hidden">
        @if ($orders->isEmpty())
            <x-admin.empty-state
                title="Belum ada transaksi"
                message="Transaksi yang masuk akan tampil di sini dan dapat diverifikasi pembayarannya."
            />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[900px]">
                    <thead>
                        <tr>
                            <th scope="col">No. Transaksi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col" class="text-right">Total</th>
                            <th scope="col">Pembayaran</th>
                            <th scope="col">Pesanan</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="font-semibold text-espresso hover:text-caramel">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap text-espresso/60">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-espresso/80">{{ $order->customer_name }}</td>
                                <td class="whitespace-nowrap text-right font-semibold text-espresso">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td><x-admin.status-badge :tone="$paymentTone($order->payment_status)">{{ $order->paymentStatusLabel() }}</x-admin.status-badge></td>
                                <td><x-admin.status-badge :tone="$orderTone($order->order_status)">{{ $order->orderStatusLabel() }}</x-admin.status-badge></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="admin-btn admin-btn-ghost !px-3 !py-1.5 text-xs">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="border-t border-softgray px-4 py-3">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
