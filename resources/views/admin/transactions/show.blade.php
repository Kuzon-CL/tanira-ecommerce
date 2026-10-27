@extends('layouts.admin')

@section('title', 'Detail Transaksi')

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
    <x-admin.page-header :title="'Transaksi ' . $order->order_number" :description="$order->created_at->format('d F Y, H:i') . ' WIB'">
        <a href="{{ route('admin.transactions.index') }}" class="admin-btn admin-btn-ghost">Kembali</a>
    </x-admin.page-header>

    <div class="grid gap-5 lg:grid-cols-[1fr_20rem]">
        <div class="space-y-5">
            {{-- Items --}}
            <div class="admin-card overflow-hidden">
                <div class="border-b border-softgray px-5 py-4">
                    <h2 class="text-sm font-bold text-espresso">Produk Dibeli</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[560px]">
                        <thead>
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col" class="text-right">Harga</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <p class="font-semibold text-espresso">{{ $item->product_name }}</p>
                                        @if ($item->sku)
                                            <p class="mt-0.5 font-mono text-xs text-espresso/45">{{ $item->sku }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap text-right text-espresso/70">Rp{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-center text-espresso/70">{{ $item->quantity }}</td>
                                    <td class="whitespace-nowrap text-right font-semibold text-espresso">Rp{{ number_format($item->line_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-softgray px-5 py-4">
                    <dl class="ml-auto max-w-xs space-y-1.5 text-sm">
                        <div class="flex justify-between gap-4 text-espresso/60">
                            <dt>Subtotal</dt>
                            <dd>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 text-espresso/60">
                            <dt>Ongkos Kirim</dt>
                            <dd>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-softgray pt-2 text-base font-bold text-espresso">
                            <dt>Total</dt>
                            <dd>Rp{{ number_format($order->total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Payments --}}
            <div class="admin-card overflow-hidden">
                <div class="border-b border-softgray px-5 py-4">
                    <h2 class="text-sm font-bold text-espresso">Pembayaran</h2>
                </div>
                @if ($order->payments->isEmpty())
                    <div class="px-5 py-6 text-sm text-espresso/55">
                        Belum ada bukti pembayaran yang diunggah pelanggan.
                    </div>
                @else
                    <ul class="divide-y divide-softgray">
                        @foreach ($order->payments as $payment)
                            <li class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-semibold text-espresso">Rp{{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        <x-admin.status-badge :tone="match($payment->status) { 'verified' => 'success', 'pending' => 'warning', 'rejected' => 'danger', default => 'neutral' }">
                                            {{ $payment->statusLabel() }}
                                        </x-admin.status-badge>
                                    </div>
                                    <p class="text-xs text-espresso/55">
                                        {{ $payment->methodLabel() }}
                                        @if ($payment->reference) &middot; Ref: {{ $payment->reference }} @endif
                                        @if ($payment->paid_at) &middot; {{ $payment->paid_at->format('d M Y, H:i') }} @endif
                                    </p>
                                    @if ($payment->verifier)
                                        <p class="text-xs text-espresso/45">Diverifikasi oleh {{ $payment->verifier->name }}</p>
                                    @endif
                                </div>
                                @if ($payment->proofUrl())
                                    <a href="{{ $payment->proofUrl() }}" target="_blank" rel="noopener" class="admin-btn admin-btn-ghost text-xs">Lihat Bukti</a>
                                @else
                                    <span class="text-xs text-espresso/40">Tanpa bukti</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Sidebar: customer + status --}}
        <div class="space-y-5">
            <div class="admin-card p-5">
                <h2 class="text-sm font-bold text-espresso">Pelanggan</h2>
                <dl class="mt-3 space-y-2.5 text-sm">
                    <div>
                        <dt class="text-xs text-espresso/45">Nama</dt>
                        <dd class="font-medium text-espresso">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-espresso/45">Email</dt>
                        <dd class="break-all text-espresso/80">{{ $order->customer_email }}</dd>
                    </div>
                    @if ($order->customer_phone)
                        <div>
                            <dt class="text-xs text-espresso/45">Telepon</dt>
                            <dd class="text-espresso/80">{{ $order->customer_phone }}</dd>
                        </div>
                    @endif
                    @if ($order->shipping_address)
                        <div>
                            <dt class="text-xs text-espresso/45">Alamat Pengiriman</dt>
                            <dd class="whitespace-pre-line text-espresso/80">{{ $order->shipping_address }}</dd>
                        </div>
                    @endif
                    @if ($order->notes)
                        <div>
                            <dt class="text-xs text-espresso/45">Catatan</dt>
                            <dd class="whitespace-pre-line text-espresso/80">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="admin-card p-5">
                <h2 class="text-sm font-bold text-espresso">Status</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    <x-admin.status-badge :tone="$paymentTone($order->payment_status)">Pembayaran: {{ $order->paymentStatusLabel() }}</x-admin.status-badge>
                    <x-admin.status-badge :tone="$orderTone($order->order_status)">Pesanan: {{ $order->orderStatusLabel() }}</x-admin.status-badge>
                </div>

                <form method="POST" action="{{ route('admin.transactions.updateStatus', $order) }}" class="mt-4 space-y-3 border-t border-softgray pt-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="payment_status" class="admin-label">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="admin-input" required>
                            @foreach (\App\Models\Order::PAYMENT_STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($order->payment_status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="order_status" class="admin-label">Status Pesanan</label>
                        <select name="order_status" id="order_status" class="admin-input" required>
                            @foreach (\App\Models\Order::ORDER_STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($order->order_status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="admin-btn admin-btn-primary w-full">Perbarui Status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
