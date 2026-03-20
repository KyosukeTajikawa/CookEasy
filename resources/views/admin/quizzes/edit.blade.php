<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クイズ編集：{{ $recipe->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.recipes.quiz.update', $recipe) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">問題文</label>
                        <textarea name="question" rows="3"
                                  class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">{{ old('question', $quiz->question) }}</textarea>
                        @error('question')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">選択肢（4つ）</label>
                        @for ($i = 0; $i < 4; $i++)
                            <div class="mb-2">
                                <input type="text" name="choices[]"
                                       value="{{ old('choices.' . $i, $quiz->choices[$i] ?? '') }}"
                                       placeholder="選択肢 {{ $i + 1 }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                            </div>
                        @endfor
                        @error('choices')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        @error('choices.*')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">正解（選択肢と完全一致）</label>
                        <input type="text" name="answer" value="{{ old('answer', $quiz->answer) }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-400">
                        @error('answer')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                            更新する
                        </button>
                        <a href="{{ route('admin.recipes.edit', $recipe) }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            キャンセル
                        </a>
                        <form method="POST" action="{{ route('admin.recipes.quiz.destroy', $recipe) }}"
                              class="ml-auto"
                              onsubmit="return confirm('このクイズを削除しますか？')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                削除
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
