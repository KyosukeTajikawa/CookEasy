<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                レシピ一覧
            </h2>
            @auth
                <a href="{{ route('recipes.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    レシピを投稿する
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- フィルターフォーム --}}
            <form method="GET" action="{{ route('recipes.index') }}"
                  class="bg-white shadow-sm rounded-lg p-4 mb-6 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">難易度</label>
                    <select name="difficulty"
                            class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                        <option value="">指定なし</option>
                        @foreach (['超簡単', '簡単', '普通'] as $level)
                            <option value="{{ $level }}" {{ request('difficulty') === $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">調理時間（〜分以内）</label>
                    <input type="number" name="cook_time" min="1"
                           value="{{ request('cook_time') }}"
                           placeholder="例：30"
                           class="w-28 border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">食材名</label>
                    <input type="text" name="ingredient"
                           value="{{ request('ingredient') }}"
                           placeholder="例：たまご"
                           class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-1.5 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        絞り込む
                    </button>
                    @if (request('difficulty') || request('cook_time') || request('ingredient'))
                        <a href="{{ route('recipes.index') }}"
                           class="px-4 py-1.5 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            リセット
                        </a>
                    @endif
                </div>
            </form>

            @if ($recipes->isEmpty())
                <p class="text-center text-gray-500 py-16">まだレシピがありません。</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($recipes as $recipe)
                        <a href="{{ route('recipes.show', $recipe) }}"
                           class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                            @php $thumbnail = $recipe->recipeImages->first(); @endphp
                            @if ($thumbnail)
                                <img src="{{ asset('storage/' . $thumbnail->image_path) }}"
                                     alt="{{ $recipe->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400 text-sm">
                                    画像なし
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $recipe->title }}</h3>
                                <div class="mt-2 flex items-center gap-3 text-sm text-gray-500">
                                    <span>⏱ {{ $recipe->cook_time }}分</span>
                                    <span>{{ $recipe->difficulty }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">{{ $recipe->user->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $recipes->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
