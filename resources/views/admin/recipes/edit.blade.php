<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('admin.recipe_edit_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('admin.recipes.update', $recipe) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- タイトル --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('recipes.field_title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title"
                               value="{{ old('title', $recipe->title) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 説明 --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('recipes.field_description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $recipe->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 調理時間 --}}
                    <div class="mb-4">
                        <label for="cook_time" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('recipes.field_cook_time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="cook_time" name="cook_time" min="1"
                               value="{{ old('cook_time', $recipe->cook_time) }}"
                               class="w-32 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cook_time') border-red-500 @enderror">
                        @error('cook_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 難易度 --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('recipes.field_difficulty') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            @foreach ([
                                '超簡単' => __('recipes.difficulty_very_easy'),
                                '簡単'   => __('recipes.difficulty_easy'),
                                '普通'   => __('recipes.difficulty_normal'),
                            ] as $value => $label)
                                <label class="flex items-center gap-1 text-sm text-gray-700">
                                    <input type="radio" name="difficulty" value="{{ $value }}"
                                           {{ old('difficulty', $recipe->difficulty) === $value ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        @error('difficulty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 既存画像 --}}
                    @if ($recipe->recipeImages->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">{{ __('recipes.current_images') }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($recipe->recipeImages->sortBy('order') as $image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt=""
                                             class="w-24 h-24 object-cover rounded-md border border-gray-200">
                                        @if ($image->is_thumbnail)
                                            <span class="absolute top-1 left-1 text-xs bg-indigo-600 text-white px-1 rounded">TOP</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 画像差し替え --}}
                    <div class="mb-6">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('recipes.field_images_replace') }}
                        </label>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-400">{{ __('recipes.image_replace_hint') }}</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.recipes.index') }}" class="text-sm text-gray-500 hover:underline">{{ __('common.cancel') }}</a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            {{ __('common.update') }}
                        </button>
                    </div>
                </form>

                {{-- クイズ管理 --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">{{ __('quiz.title', ['title' => '']) }}</h3>
                        @if ($recipe->quiz)
                            <div class="flex gap-2">
                                <a href="{{ route('admin.recipes.quiz.edit', $recipe) }}"
                                   class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    {{ __('admin.quiz_update_button') }}
                                </a>
                            </div>
                        @else
                            <a href="{{ route('admin.recipes.quiz.create', $recipe) }}"
                               class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                {{ __('admin.quiz_create_button') }}
                            </a>
                        @endif
                    </div>
                    @if ($recipe->quiz)
                        <p class="text-sm text-gray-600">{{ $recipe->quiz->question }}</p>
                    @else
                        <p class="text-sm text-gray-400">クイズはまだ設定されていません。</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
