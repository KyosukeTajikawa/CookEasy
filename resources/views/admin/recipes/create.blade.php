<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理画面 - レシピを追加
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('admin.recipes.store') }}">
                    @csrf

                    {{-- タイトル --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            レシピ名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title"
                               value="{{ old('title') }}"
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
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                               value="{{ old('cook_time') }}"
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
                                           {{ old('difficulty') === $level ? 'checked' : '' }}
                                           class="text-indigo-600">
                                    {{ $level }}
                                </label>
                            @endforeach
                        </div>
                        @error('difficulty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.recipes.index') }}" class="text-sm text-gray-500 hover:underline">キャンセル</a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            公開する
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
