<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') &middot; Tanira</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700" rel="stylesheet">
</head>
<body class="admin-shell min-h-screen">
    <div class="lg:flex">
        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="admin-sidebar fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col transition-transform duration-200 ease-out lg:static lg:translate-x-0" aria-label="Navigasi admin">
            <div class="flex items-center justify-between px-5 pb-5 pt-6">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="block font-display text-xl tracking-tight text-cream">TANIRA</a>
                    <span class="mt-0.5 block text-[0.625rem] font-semibold uppercase tracking-[0.22em] text-cream/50">Admin Panel</span>
                </div>
                <button type="button" id="sidebar-close" class="icon-btn text-cream/70 hover:bg-white/10 hover:text-cream lg:hidden" aria-label="Tutup menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 px-5" aria-label="Menu utama">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
                        ['route' => 'admin.products.index', 'label' => 'Produk', 'pattern' => 'admin.products.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'admin.transactions.index', 'label' => 'Transaksi', 'pattern' => 'admin.transactions.*', 'icon' => 'M4 4h16v4H4zM4 12h16v8H4z'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="admin-nav-link {{ request()->routeIs($item['pattern']) ? 'is-active' : '' }}"
                       @if (request()->routeIs($item['pattern'])) aria-current="page" @endif>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-6 border-t border-cream/10 px-5 py-4">
                <div class="mb-3">
                    <p class="truncate text-sm font-semibold text-cream">{{ auth()->user()?->name }}</p>
                    <p class="truncate text-xs text-cream/50">{{ auth()->user()?->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-nav-link w-full text-left text-cream/70 hover:bg-white/10 hover:text-cream">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-espresso/40 lg:hidden" aria-hidden="true"></div>

        {{-- Main --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="flex items-center gap-3 border-b border-softgray bg-white px-4 py-3 lg:hidden">
                <button type="button" id="sidebar-open" class="icon-btn" aria-label="Buka menu" aria-controls="admin-sidebar" aria-expanded="false">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-display text-base tracking-tight text-espresso">TANIRA</span>
                <span class="text-[0.625rem] font-semibold uppercase tracking-[0.18em] text-espresso/40">Admin</span>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-9">
                @if (session('status'))
                    <div class="mb-5 flex items-start gap-2.5 rounded-lg border border-[#c9e6d2] bg-[#eef8f1] px-4 py-3 text-sm text-[#1c7c3f]" role="status">
                        <svg class="mt-0.5 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-[#f0c9c4] bg-[#fdf3f2] px-4 py-3 text-sm text-[#b42318]" role="alert">
                        <p class="font-semibold">Terjadi kesalahan:</p>
                        <ul class="mt-1 list-inside list-disc space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');
            if (!sidebar || !overlay) return;

            const setOpen = (open) => {
                sidebar.classList.toggle('-translate-x-full', !open);
                overlay.classList.toggle('hidden', !open);
                openBtn?.setAttribute('aria-expanded', String(open));
                document.body.classList.toggle('overflow-hidden', open);
            };

            openBtn?.addEventListener('click', () => setOpen(true));
            closeBtn?.addEventListener('click', () => setOpen(false));
            overlay.addEventListener('click', () => setOpen(false));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') setOpen(false);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
