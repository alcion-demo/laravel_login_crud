<button {{ $attributes->merge([
    'class' => 'inline-flex items-center px-4 py-2 rounded'
]) }}>
    {{ $slot }}
</button>
