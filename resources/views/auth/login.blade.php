<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Admin &middot; Tanira</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700" rel="stylesheet">
</head>
<body class="admin-shell flex min-h-screen items-center justify-center px-5 py-10">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <span class="font-display text-2xl tracking-tight text-espresso">TANIRA</span>
            <p class="mt-1 text-[0.625rem] font-semibold uppercase tracking-[0.22em] text-espresso/45">Admin Panel</p>
        </div>

        <div class="admin-card p-6 sm:p-7">
            <h1 class="text-lg font-bold text-espresso">Masuk ke Dashboard</h1>
            <p class="mt-1 text-sm text-espresso/55">Gunakan akun admin untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-lg border border-[#f0c9c4] bg-[#fdf3f2] px-3.5 py-2.5 text-sm text-[#b42318]" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="admin-input @error('email') has-error @enderror" required autofocus autocomplete="username">
                </div>

                <div>
                    <label for="password" class="admin-label">Kata Sandi</label>
                    <input type="password" name="password" id="password"
                           class="admin-input @error('password') has-error @enderror" required autocomplete="current-password">
                </div>

                <label for="remember" class="flex cursor-pointer items-center gap-2 text-sm text-espresso/70">
                    <input type="checkbox" name="remember" id="remember" value="1"
                           class="h-4 w-4 rounded border-softgray text-caramel focus:ring-caramel">
                    Ingat saya
                </label>

                <button type="submit" class="admin-btn admin-btn-primary w-full">Masuk</button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-espresso/40">&copy; {{ date('Y') }} Toko Kopi Tanira</p>
    </div>
</body>
</html>
