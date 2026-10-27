<header class="sticky top-0 z-50 bg-cream/90 backdrop-blur-sm">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-6 px-5 sm:h-[4.5rem] lg:px-8">
        <a href="/" class="font-display text-xl tracking-tight text-espresso sm:text-[1.375rem]">
            TANIRA
        </a>

        <nav class="hidden items-center gap-8 md:flex" aria-label="Navigasi utama">
            <a href="#beranda" class="nav-link">Beranda</a>
            <a href="#produk" class="nav-link">Produk</a>
            <a href="#tentang" class="nav-link">Tentang Kami</a>
        </nav>

        <div class="flex items-center gap-1 sm:gap-2">
            <button type="button" class="icon-btn" aria-label="Cari produk">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-3.5-3.5"></path>
                </svg>
            </button>

            <button type="button" class="icon-btn" aria-label="Keranjang belanja">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                    <path d="M3 6h18"></path>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </button>

            <a href="#kontak" class="btn-ghost ml-2 hidden text-sm sm:inline-flex">Hubungi Kami</a>

            <button type="button" id="menu-toggle" class="icon-btn md:hidden" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 17h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden border-t border-espresso/10 bg-cream md:hidden">
        <nav class="mx-auto flex max-w-6xl flex-col gap-1 px-5 py-4" aria-label="Navigasi mobile">
            <a href="#beranda" class="rounded-lg px-3 py-2.5 text-sm font-medium text-espresso/80 hover:bg-espresso/5">Beranda</a>
            <a href="#produk" class="rounded-lg px-3 py-2.5 text-sm font-medium text-espresso/80 hover:bg-espresso/5">Produk</a>
            <a href="#tentang" class="rounded-lg px-3 py-2.5 text-sm font-medium text-espresso/80 hover:bg-espresso/5">Tentang Kami</a>
            <a href="#kontak" class="rounded-lg px-3 py-2.5 text-sm font-medium text-espresso/80 hover:bg-espresso/5">Hubungi Kami</a>
        </nav>
    </div>
</header>