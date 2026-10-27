@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Dashboard" description="Ringkasan operasional Toko Kopi Tanira.">
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Produk
        </a>
    </x-admin.page-header>

    @if ($pendingPayment > 0)
        <div class="mb-5 flex flex-wrap items-center gap-2 rounded-lg border border-[#f0e0c4] bg-[#fdf6ea] px-4 py-3 text-sm text-[#96610c]">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/>
            </svg>
            <span><strong>{{ $pendingPayment }}</strong> pesanan menunggu konfirmasi pembayaran.</span>
            <a href="{{ route('admin.transactions.index', ['payment_status' => 'pending']) }}" class="font-semibold underline underline-offset-2 hover:opacity-75">Lihat</a>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-admin.stat-card
            label="Pelanggan"
            :value="number_format($customerCount, 0, ',', '.')"
            hint="Pelanggan terdaftar"
            icon="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 0 8 4 4 0 0 0 0-8M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
        />
        <x-admin.stat-card
            label="Transaksi"
            :value="number_format($orderCount, 0, ',', '.')"
            hint="Total transaksi tercatat"
            icon="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"
        />
        <x-admin.stat-card
            label="Produk"
            :value="number_format($productCount, 0, ',', '.')"
            hint="Produk tersedia"
            icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
        />
    </div>

    <div class="admin-card mt-6 overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-softgray px-5 py-4">
            <h2 class="text-sm font-bold text-espresso">Transaksi Terbaru</h2>
            <a href="{{ route('admin.transactions.index') }}" class="text-xs font-semibold text-caramel hover:opacity-75">Lihat semua</a>
        </div>

        @if ($latestOrders->isEmpty())
            <x-admin.empty-state
                title="Belum ada transaksi"
                message="Transaksi akan muncul di sini setelah pelanggan melakukan pemesanan."
            />
        @else
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[680px]">
                    <thead>
                        <tr>
                            <th scope="col">No. Transaksi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col" class="text-right">Total</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="font-semibold text-espresso hover:text-caramel">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap text-espresso/60">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="text-espresso/80">{{ $order->customer_name }}</td>
                                <td class="whitespace-nowrap text-right font-semibold text-espresso">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $tone = match ($order->payment_status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'failed', 'refunded' => 'danger',
                                            default => 'neutral',
                                        };
                                    @endphp
                                    <x-admin.status-badge :tone="$tone">{{ $order->paymentStatusLabel() }}</x-admin.status-badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
