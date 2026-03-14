<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            レビュー管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 rounded p-3">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">投稿者</th>
                            <th class="px-4 py-3">レシピ</th>
                            <th class="px-4 py-3">評価</th>
                            <th class="px-4 py-3">コメント</th>
                            <th class="px-4 py-3">投稿日</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($reviews as $review)
                            <tr>
                                <td class="px-4 py-3">{{ $review->user->name }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('recipes.show', $review->recipe) }}"
                                       class="text-indigo-600 hover:underline">
                                        {{ $review->recipe->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-yellow-500">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </td>
                                <td class="px-4 py-3">{{ Str::limit($review->comment, 50) }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $review->created_at->format('Y/m/d') }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                          onsubmit="return confirm('このレビューを削除しますか？')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-400">レビューはありません。</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
