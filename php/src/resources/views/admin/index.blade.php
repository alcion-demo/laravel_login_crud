<x-admin-app>
    <div class="max-w-7xl mx-auto">
        <x-message type="status" />
        <div class="flex items-center justify-between mb-8">
            <div class="pt-2">
                <div class="flex items-baseline gap-2">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tighter">
                        User Management
                    </h1>
                    <span class="text-sm font-medium text-gray-400">/ ユーザー管理</span>
                </div>
                <div class="mt-2 w-8 h-0.5 bg-blue-500 rounded-full"></div>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center bg-white shadow-sm border border-gray-200 rounded-xl p-1">
                <input type="text" name="keyword" placeholder="ユーザーを検索..." value="{{ $keyword }}" 
                    class="border-0 focus:ring-0 text-sm px-4 py-2 w-64 rounded-l-lg">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition">
                    検索
                </button>
                @if(!empty(request('keyword')))
                    <a href="{{ route('admin.users.index') }}" class="px-3 text-gray-400 hover:text-gray-600 text-xs font-bold">クリア</a>
                @endif
            </form>
        </div>

        <div class="mb-4">{{ $users->links() }}</div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">名前</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">メール</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">権限</th>
                        {{-- ★ text-center を追加して見出しを中央に --}}
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4 text-sm font-mono text-gray-400">#{{ $user->id }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[10px] font-black rounded {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $user->is_admin ? 'ADMIN' : 'USER' }}
                                </span>
                            </td>
                            {{-- ★ tdに text-center を追加し、中のflexを inline-flex に変更 --}}
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 bg-indigo-500 text-white text-xs font-medium rounded-md hover:bg-indigo-600 transition">
                                        編集
                                    </a>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('本当に削除しますか？')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded-md hover:bg-red-600 transition">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-app>