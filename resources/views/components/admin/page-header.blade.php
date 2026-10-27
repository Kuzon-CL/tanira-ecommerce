@props(['title', 'description' => null])
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-espresso sm:text-[1.75rem]">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-espresso/55">{{ $description }}</p>
        @endif
    </div>
    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
