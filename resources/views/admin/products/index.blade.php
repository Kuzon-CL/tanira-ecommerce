@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <x-admin.page-header title="Produk" description="Kelola katalog produk Toko Kopi Tanira.">
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Produk
        </a>
    </x-admin.page-header>

    <form method="GET" action="{{ route('admin.products.index') }}" class="admin-card mb-5 p-4">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto]">
            <div>
                <label for="category" class="admin-label">Jenis Produk</label>
                <select name="category" id="category" class="admin-input">
                    <option value="">Semua Jenis</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="q" class="admin-label">Cari Produk</label>
                <input type="search" name="q" id="q" value="{{ $search }}" placeholder="Nama atau SKU..." class="admin-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="admin-btn admin-btn-primary w-full sm:w-auto">Cari</button>
                @if ($search !== '' || $categoryId)
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <div class="admin-card overflow-hidden">
        @if ($products->isEmpty())
            <x-admin.empty-state
                title="Produk tidak ditemukan"
                message="{{ $search !== '' || $categoryId ? 'Coba ubah kata kunci atau filter kategori.' : 'Belum ada produk. Tambahkan produk pertama Anda.' }}"
            >
                @if ($search === '' && ! $categoryId)
                    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">Tambah Produk</a>
                @endif
            </x-admin.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table min-w-[900px]">
                    <thead>
                        <tr>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="text-right">Harga</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-lg border border-softgray bg-cream">
                                        @if ($product->imageUrl())
                                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="h-full w-full object-cover" loading="lazy">
                                        @else
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="text-espresso/30" aria-hidden="true">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <p class="font-semibold text-espresso">{{ $product->name }}</p>
                                    @if ($product->description)
                                        <p class="mt-0.5 max-w-[16rem] truncate text-xs text-espresso/45">{{ $product->description }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap font-mono text-xs text-espresso/60">{{ $product->sku }}</td>
                                <td class="whitespace-nowrap text-espresso/70">{{ $product->category?->name ?? '-' }}</td>
                                <td class="whitespace-nowrap text-right font-semibold text-espresso">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $stockTone = match ($product->stockStatus()) {
                                            'tersedia' => 'success',
                                            'menipis' => 'warning',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <x-admin.status-badge :tone="$stockTone">{{ $product->stock }} &middot; {{ $product->stockStatusLabel() }}</x-admin.status-badge>
                                </td>
                                <td>
                                    <x-admin.status-badge :tone="$product->is_active ? 'success' : 'neutral'">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-admin.status-badge>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-espresso/60 transition-colors hover:bg-cream hover:text-caramel" aria-label="Edit {{ $product->name }}" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-espresso/60 transition-colors hover:bg-[#fdf3f2] hover:text-[#b42318]" aria-label="Hapus {{ $product->name }}" title="Hapus">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="border-t border-softgray px-4 py-3">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
