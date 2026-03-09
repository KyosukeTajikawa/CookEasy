<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ブックマーク一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if ($recipes->isEmpty())
                <p class="text-center text-gray-500 py-16">ブックマークしたレシピがありません。</p>
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
