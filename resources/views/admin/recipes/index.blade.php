<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                管理画面 - レシピ一覧
            </h2>
            <a href="{{ route('admin.recipes.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                レシピを追加
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タイトル</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">投稿者</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">投稿日</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recipes as $recipe)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $recipe->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $recipe->user->name }}</td>
                                <td class="px-6 py-4">
                                    @if ($recipe->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">承認待ち</span>
                                    @elseif ($recipe->status === 'published')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">公開中</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">却下</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $recipe->created_at->format('Y/m/d') }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($recipe->status === 'pending')
                                            <form method="POST" action="{{ route('admin.recipes.approve', $recipe) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                    承認
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.recipes.reject', $recipe) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                    却下
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.recipes.edit', $recipe) }}"
                                           class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('admin.recipes.destroy', $recipe) }}"
                                              onsubmit="return confirm('削除しますか？')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200">
                                                削除
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    レシピがありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $recipes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
