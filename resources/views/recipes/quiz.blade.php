<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('quiz.title', ['title' => $recipe->title]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 結果表示 --}}
            @if (session('quiz_result'))
                @if (session('quiz_result') === 'correct')
                    <div class="bg-green-100 border border-green-300 text-green-800 rounded-lg p-4 text-center font-semibold">
                        {{ __('quiz.correct') }}
                    </div>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('recipes.index') }}"
                           class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                            {{ __('common.back_to_list') }}
                        </a>
                        <a href="{{ route('recipes.show', $recipe) }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            {{ __('quiz.back_to_recipe') }}
                        </a>
                    </div>
                @else
                    <div class="bg-red-100 border border-red-300 text-red-800 rounded-lg p-4 text-center font-semibold">
                        {{ __('quiz.incorrect', ['answer' => $quiz->answer]) }}
                    </div>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('recipes.show', $recipe) }}"
                           class="px-4 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                            {{ __('quiz.back_to_recipe') }}
                        </a>
                        <a href="{{ route('recipes.quiz.show', $recipe) }}"
                           class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                            🔁 {{ __('quiz.answer_button') }}
                        </a>
                    </div>
                @endif
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <p class="text-lg font-medium text-gray-800 mb-6">{{ $quiz->question }}</p>

                @auth
                    <form method="POST" action="{{ route('quiz.answer', $recipe) }}">
                        @csrf
                        <div class="space-y-3">
                            @foreach ($quiz->choices as $choice)
                                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50
                                    {{ session('quiz_result') && session('selected_answer') === $choice
                                        ? (session('quiz_result') === 'correct' ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50')
                                        : 'border-gray-200' }}">
                                    <input type="radio" name="answer" value="{{ $choice }}"
                                           class="text-indigo-600"
                                           {{ session('selected_answer') === $choice ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $choice }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('answer')
                            <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                        <div class="mt-6">
                            <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                                {{ __('quiz.answer_button') }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="space-y-3">
                        @foreach ($quiz->choices as $choice)
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                                <span class="text-sm text-gray-700">{{ $choice }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-500">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ __('quiz.login_link') }}</a>
                        {{ __('quiz.answer_button') }}
                    </p>
                @endauth
            </div>

            <div class="text-center">
                <a href="{{ route('recipes.show', $recipe) }}" class="text-sm text-indigo-600 hover:underline">{{ __('quiz.back_to_recipe') }}</a>
            </div>

        </div>
    </div>
</x-app-layout>
