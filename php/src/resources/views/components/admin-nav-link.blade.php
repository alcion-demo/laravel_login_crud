@props(['href', 'active' => false])

@php
$classes = $active
    ? 'block px-4 py-2 rounded bg-blue-500 text-white'
    : 'block px-4 py-2 rounded text-gray-600 hover:bg-gray-100 transition';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>