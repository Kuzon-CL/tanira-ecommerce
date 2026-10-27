@extends('layouts.app')

@section('title', 'Tanira - Temukan Rasa, Nikmati Cerita')

@section('content')
    {{-- HERO --}}
    <section id="beranda" class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 hero-glow" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-5 pb-10 pt-12 sm:pt-16 lg:px-8 lg:pb-14 lg:pt-20">
            <div class="mx-auto max-w-3xl text-center">
                <span class="section-label reveal">Kopi Pilihan</span>

                <h1 class="display-tight reveal mt-5 text-[2.75rem] text-espresso sm:text-6xl lg:text-[5.25rem]" style="--reveal-delay: 80ms">
                    Temukan Rasa,<br class="hidden sm:block">
                    Nikmati Cerita.
                </h1>

                <p class="reveal mx-auto mt-6 max-w-xl text-[0.9375rem] leading-relaxed text-espresso/70 sm:text-base" style="--reveal-delay: 160ms">
                    Jelajahi pilihan kopi Tanira, temukan karakter rasa favoritmu, dan nikmati pengalaman kopi yang lebih dekat dengan keseharianmu.
                </p>

                <div class="reveal mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-5" style="--reveal-delay: 240ms">
                    <a href="#produk" class="btn-primary">
                        Jelajahi Produk
                        <svg class="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>
                    </a>
                    <a href="#tentang" class="btn-ghost">Kenali Tanira</a>
                </div>
            </div>

            <div class="reveal relative mx-auto mt-10 flex max-w-md justify-center sm:mt-8 sm:max-w-lg lg:mt-4 lg:max-w-xl" style="--reveal-delay: 320ms">
                <img
                    src="{{ asset('images/kopiibg.png') }}"
                    alt="Kemasan kopi Tanira"
                    width="471"
                    height="530"
                    class="h-auto w-full max-w-[300px] object-contain drop-shadow-[0_28px_45px_rgba(43,33,27,0.18)] sm:max-w-[360px] lg:max-w-[430px]"
                    fetchpriority="high"
                    decoding="async"
                >
            </div>
        </div>
    </section>

    {{-- PRODUK --}}
    <section id="produk" class="border-t border-espresso/10 py-16 lg:py-24">
        <div class="mx-auto max-w-6xl px-5 lg:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="reveal">
                    <span class="section-label">Koleksi</span>
                    <h2 class="display-tight mt-4 text-3xl text-espresso sm:text-4xl lg:text-5xl">Temukan Favoritmu</h2>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-espresso/65 sm:text-[0.9375rem]">
                        Pilihan kopi untuk menemani setiap momen.
                    </p>
                </div>

                <a href="#produk" class="btn-ghost reveal hidden shrink-0 sm:inline-flex" style="--reveal-delay: 80ms">
                    Lihat Semua Produk
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"></path>
                        <path d="m13 6 6 6-6 6"></path>
                    </svg>
                </a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:mt-14 lg:grid-cols-3 lg:gap-8">
                @foreach (config('store.products') as $index => $product)
                    <article class="product-card reveal" style="--reveal-delay: {{ $index * 80 }}ms">
                        <a href="#produk" class="product-media">
                            <img
                                src="{{ asset($product['image']) }}"
                                alt="Kemasan {{ $product['name'] }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>

                        <div class="mt-5 flex flex-1 flex-col">
                            <h3 class="text-lg font-semibold tracking-tight text-espresso">{{ $product['name'] }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-espresso/60">{{ $product['character'] }}</p>

                            <div class="mt-4 flex items-center justify-between gap-4">
                                <span class="text-[0.9375rem] font-semibold text-espresso">
                                    Rp{{ number_format($product['price'], 0, ',', '.') }}
                                </span>
                                <a href="#produk" class="text-sm font-semibold text-caramel transition-opacity hover:opacity-70">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <a href="#produk" class="btn-ghost mt-10 inline-flex sm:hidden">Lihat Semua Produk</a>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="border-t border-espresso/10 py-16 lg:py-24">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-5 lg:grid-cols-2 lg:gap-16 lg:px-8">
            <div class="reveal">
                <span class="section-label">Tentang Tanira</span>
                <h2 class="display-tight mt-4 text-3xl text-espresso sm:text-4xl lg:text-5xl">Lebih Dekat dengan Kopi Pilihan.</h2>
                <p class="mt-5 max-w-md text-sm leading-relaxed text-espresso/70 sm:text-[0.9375rem]">
                    Tanira adalah toko kopi yang hadir untuk menemani keseharianmu. Kami percaya secangkir kopi bukan sekadar minuman, tetapi bagian dari cerita yang kamu jalani setiap hari.
                </p>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-espresso/70 sm:text-[0.9375rem]">
                    Setiap kemasan dipilih dengan teliti agar mudah dinikmati, baik untuk pengalaman kopi pertamamu maupun untuk penikmat kopi yang sudah terbiasa.
                </p>
                <a href="#produk" class="btn-ghost mt-7">Lihat Pilihan Kopi</a>
            </div>

            <div class="reveal flex justify-center lg:justify-end" style="--reveal-delay: 80ms">
                <div class="flex aspect-square w-full max-w-md items-center justify-center rounded-3xl bg-[#efe7da]">
                    <img
                        src="{{ asset('images/kopiibg.png') }}"
                        alt="Produk kopi Tanira"
                        class="h-3/4 w-3/4 object-contain"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
            </div>
        </div>
    </section>
@endsection