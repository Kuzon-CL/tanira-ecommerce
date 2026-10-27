@props(['label', 'value', 'icon' => null, 'hint' => null])
<div class="admin-card p-5">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-espresso/45">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold leading-none tracking-tight text-espresso">{{ $value }}</p>
            @if ($hint)
                <p class="mt-2 text-xs text-espresso/50">{{ $hint }}</p>
            @endif
        </div>
        @if ($icon)
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-cream text-caramel">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="{{ $icon }}"/>
                </svg>
            </span>
        @endif
    </div>
</div>
