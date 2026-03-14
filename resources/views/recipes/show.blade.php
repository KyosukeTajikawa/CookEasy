<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $recipe->title }}
            </h2>
            <div class="flex gap-2">
                @auth
                    @if ($isBookmarked)
                        <form method="POST" action="{{ route('bookmarks.destroy', $recipe) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-sm bg-yellow-400 text-white rounded hover:bg-yellow-500">
                                ★ 保存済み
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('bookmarks.store', $recipe) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                ☆ 保存する
                            </button>
                        </form>
                    @endif
                @endauth

                @can('update', $recipe)
                    <a href="{{ route('recipes.edit', $recipe) }}"
                       class="px-3 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        編集
                    </a>
                    <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                          onsubmit="return confirm('このレシピを削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                            削除
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 画像ギャラリー --}}
            @php
                $sortedImages = $recipe->recipeImages->sortBy('order');
                $thumbnail = $sortedImages->firstWhere('is_thumbnail', true) ?? $sortedImages->first();
            @endphp
            @if ($thumbnail)
                <img src="{{ asset('storage/' . $thumbnail->image_path) }}"
                     alt="{{ $recipe->title }}"
                     class="w-full rounded-lg shadow object-cover max-h-80">
            @endif
            @if ($sortedImages->count() > 1)
                <div class="flex gap-2 overflow-x-auto">
                    @foreach ($sortedImages as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="{{ $recipe->title }}"
                             class="w-24 h-24 flex-shrink-0 object-cover rounded-md border border-gray-200">
                    @endforeach
                </div>
            @endif

            {{-- 基本情報 --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex gap-6 text-sm text-gray-600 mb-4">
                    <span>⏱ 調理時間：{{ $recipe->cook_time }}分</span>
                    <span>難易度：{{ $recipe->difficulty }}</span>
                    <span>投稿者：{{ $recipe->user->name }}</span>
                </div>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $recipe->description }}</p>
            </div>

            {{-- 材料 --}}
            @if ($recipe->ingredients->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">材料</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recipe->ingredients as $ingredient)
                            <li class="py-2 flex justify-between text-sm text-gray-700">
                                <span>{{ $ingredient->name }}</span>
                                <span class="text-gray-500">{{ $ingredient->quantity }}{{ $ingredient->unit }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 手順 --}}
            @if ($recipe->steps->isNotEmpty())
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">作り方</h3>
                    <ol class="space-y-4">
                        @foreach ($recipe->steps as $step)
                            <li class="flex gap-4">
                                <span class="flex-shrink-0 w-7 h-7 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $step->description }}</p>
                                    @if ($step->image_path)
                                        <img src="{{ asset('storage/' . $step->image_path) }}"
                                             alt="手順{{ $loop->iteration }}"
                                             class="mt-2 rounded-md max-h-48 object-cover">
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

            {{-- レビュー --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">レビュー（{{ $recipe->reviews->count() }}件）</h3>

                @if (session('success'))
                    <div class="mb-4 text-sm text-green-700 bg-green-100 rounded p-3">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 text-sm text-red-700 bg-red-100 rounded p-3">{{ session('error') }}</div>
                @endif

                @forelse ($recipe->reviews as $review)
                    <div class="border-b border-gray-100 py-3 last:border-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="font-medium text-gray-700">{{ $review->user->name }}</span>
                                <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                <span class="text-gray-400 text-xs">{{ $review->created_at->format('Y/m/d') }}</span>
                            </div>
                            @can('delete', $review)
                                <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                                      onsubmit="return confirm('このレビューを削除しますか？')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">削除</button>
                                </form>
                            @endcan
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">まだレビューはありません。</p>
                @endforelse

                @auth
                    @php
                        $hasReviewed = $recipe->reviews->contains('user_id', auth()->id());
                    @endphp
                    @unless ($hasReviewed)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">レビューを投稿する</h4>
                            <form method="POST" action="{{ route('reviews.store', $recipe) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm text-gray-600 mb-1">評価</label>
                                    <select name="rating" class="border border-gray-300 rounded px-3 py-1 text-sm">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                                {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm text-gray-600 mb-1">コメント</label>
                                    <textarea name="comment" rows="3"
                                              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400"
                                              placeholder="レシピの感想を書いてください">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    投稿する
                                </button>
                            </form>
                        </div>
                    @endunless
                @endauth
            </div>

            <div class="text-center">
                <a href="{{ route('recipes.index') }}" class="text-sm text-indigo-600 hover:underline">← レシピ一覧に戻る</a>
            </div>

        </div>
    </div>
</x-app-layout>
