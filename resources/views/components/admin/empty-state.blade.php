@props(['message', 'title' => 'Belum ada data'])
<div class="flex flex-col items-center justify-center px-6 py-14 text-center">
    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-cream text-caramel">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 7h18M6 7v13h12V7M9 11h6"/>
        </svg>
    </span>
    <p class="mt-3 text-sm font-semibold text-espresso">{{ $title }}</p>
    <p class="mt-1 max-w-sm text-sm text-espresso/55">{{ $message }}</p>
    @if (! $slot->isEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
