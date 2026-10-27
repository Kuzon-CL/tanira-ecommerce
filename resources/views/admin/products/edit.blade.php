@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <x-admin.page-header title="Edit Produk" :description="$product->name">
        <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost">Kembali</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form', ['product' => $product, 'categories' => $categories])

        <div class="mt-6 flex flex-wrap items-center gap-2">
            <button type="submit" class="admin-btn admin-btn-primary">Perbarui Produk</button>
            <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost">Batal</a>
        </div>
    </form>
@endsection
