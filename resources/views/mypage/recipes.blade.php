<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('mypage.title') }}</h2>
            <a href="{{ route('recipes.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                {{ __('mypage.post_button') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($recipes->isEmpty())
                <p class="text-center text-gray-500 py-16">{{ __('mypage.no_recipes') }}</p>
            @else
                <div class="space-y-3">
                    @foreach ($recipes as $recipe)
                        @php
                            [$badgeClass, $badgeLabel] = match($recipe->status) {
                                'published' => ['bg-green-100 text-green-800', __('mypage.status_published')],
                                'rejected'  => ['bg-red-100 text-red-800', __('mypage.status_rejected')],
                                default     => ['bg-yellow-100 text-yellow-800', __('mypage.status_pending')],
                            };
                        @endphp
                        <div class="bg-white shadow-sm rounded-lg p-4 flex items-center gap-4">
                            @php $thumbnail = $recipe->recipeImages->first(); @endphp
                            @if ($thumbnail)
                                <img src="{{ asset('storage/' . $thumbnail->image_path) }}"
                                     alt="{{ $recipe->title }}"
                                     class="w-16 h-16 object-cover rounded-md flex-shrink-0">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 flex items-center justify-center text-gray-400 text-xs">
                                    {{ __('common.no_image') }}
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $recipe->created_at->format('Y/m/d') }}</span>
                                </div>
                                <p class="font-medium text-gray-800 truncate">{{ $recipe->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">⏱ {{ $recipe->cook_time }}{{ __('recipes.cook_time_minutes') }} / {{ $recipe->difficulty }}</p>
                            </div>

                            <div class="flex gap-2 flex-shrink-0">
                                @if ($recipe->status === 'published')
                                    <a href="{{ route('recipes.show', $recipe) }}"
                                       class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        {{ __('mypage.show_button') }}
                                    </a>
                                @endif
                                <a href="{{ route('recipes.edit', $recipe) }}"
                                   class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    {{ __('mypage.edit_button') }}
                                </a>
                                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                                      onsubmit="return confirm('{{ __('mypage.delete_confirm') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                        {{ __('mypage.delete_button') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
