@props(['tone' => 'neutral'])
@php
    $toneClass = match ($tone) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
        default => 'badge-neutral',
    };
@endphp
<span {{ $attributes->merge(['class' => 'badge '.$toneClass]) }}>{{ $slot }}</span>
