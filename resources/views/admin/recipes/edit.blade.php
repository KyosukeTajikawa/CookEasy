<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理画面 - レシピを編集
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
                            レシピ名 <span class="text-red-500">*</span>
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
                            説明 <span class="text-red-500">*</span>
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
                            調理時間（分） <span class="text-red-500">*</span>
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
                            難易度 <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            @foreach (['超簡単', '簡単', '普通'] as $level)
                                <label class="flex items-center gap-1 text-sm text-gray-700">
                                    <input type="radio" name="difficulty" value="{{ $level }}"
                                           {{ old('difficulty', $recipe->difficulty) === $level ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    {{ $level }}
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
                            <p class="text-sm font-medium text-gray-700 mb-2">現在の画像</p>
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
                            画像を差し替える（複数選択可）
                        </label>
                        <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-400">選択した場合、既存の画像はすべて置き換えられます。1枚目がサムネイルになります。</p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.recipes.index') }}" class="text-sm text-gray-500 hover:underline">キャンセル</a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            更新する
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
