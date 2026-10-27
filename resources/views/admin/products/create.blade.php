@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <x-admin.page-header title="Tambah Produk" description="Tambahkan produk baru ke katalog Tanira.">
        <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost">Kembali</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form', ['product' => $product, 'categories' => $categories])

        <div class="mt-6 flex flex-wrap items-center gap-2">
            <button type="submit" class="admin-btn admin-btn-primary">Simpan Produk</button>
            <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn-ghost">Batal</a>
        </div>
    </form>
@endsection
