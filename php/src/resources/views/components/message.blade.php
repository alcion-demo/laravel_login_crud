@props(['type' => 'status', 'message' => null])

@if ($errors->any())
    <div class="text-sm text-red-600 leading-tight mb-4">
        @foreach ($errors->all() as $error)
            <p class="m-0 p-0 leading-tight mt-1">{{ $error }}</p>
        @endforeach
    </div>
@elseif (session($type) || $message)
    <div class="border px-4 py-2 rounded mb-4
        {{ $type === 'status' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-blue-100 border-blue-400 text-blue-700' }}">
        {{ $message ?? session($type) }}
    </div>
@endif
