<x-app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>

                <!-- CRUDリンク -->
                <div class="p-6 text-gray-900 space-x-4">
                    <a href="{{ route('todos.index') }}" class="text-blue-600 hover:underline">Todo 一覧</a>
                    <a href="{{ route('todos.create') }}" class="text-green-600 hover:underline">Todo 作成</a>
                </div>
            </div>
        </div>
    </div>
</x-app>
