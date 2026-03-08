<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            レシピを編集する
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('recipes.update', $recipe) }}" enctype="multipart/form-data">
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

                    {{-- 食材 --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">食材</label>
                        <div id="ingredients-container" class="space-y-2">
                            @forelse ($recipe->ingredients as $i => $ingredient)
                                <div class="ingredient-row flex gap-2">
                                    <input type="text" name="ingredients[{{ $i }}][name]"
                                           value="{{ old("ingredients.{$i}.name", $ingredient->name) }}"
                                           placeholder="食材名"
                                           class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <input type="text" name="ingredients[{{ $i }}][quantity]"
                                           value="{{ old("ingredients.{$i}.quantity", $ingredient->quantity) }}"
                                           placeholder="分量"
                                           class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <input type="text" name="ingredients[{{ $i }}][unit]"
                                           value="{{ old("ingredients.{$i}.unit", $ingredient->unit) }}"
                                           placeholder="単位"
                                           class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <button type="button" onclick="this.closest('.ingredient-row').remove()"
                                            class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                                </div>
                            @empty
                                <div class="ingredient-row flex gap-2">
                                    <input type="text" name="ingredients[0][name]" placeholder="食材名"
                                           class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <input type="text" name="ingredients[0][quantity]" placeholder="分量"
                                           class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <input type="text" name="ingredients[0][unit]" placeholder="単位"
                                           class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <button type="button" onclick="this.closest('.ingredient-row').remove()"
                                            class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" onclick="addIngredient()"
                                class="mt-2 text-sm text-indigo-600 hover:underline">+ 食材を追加</button>
                    </div>

                    {{-- 手順 --}}
                    @php $stepCount = $recipe->steps->count(); @endphp
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">手順</label>
                        <div id="steps-container" class="space-y-3">
                            @forelse ($recipe->steps as $i => $step)
                                <div class="step-row border border-gray-200 rounded-md p-3">
                                    <div class="flex items-start gap-2">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold mt-1 step-num">{{ $i + 1 }}</span>
                                        <div class="flex-1">
                                            <textarea name="steps[{{ $i }}][description]" rows="2" placeholder="手順の説明"
                                                      class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old("steps.{$i}.description", $step->description) }}</textarea>
                                            @if ($step->image_path)
                                                <img src="{{ asset('storage/' . $step->image_path) }}" alt="" class="mt-1 h-16 rounded">
                                            @endif
                                            <input type="file" name="step_images[{{ $i }}]" accept="image/jpeg,image/png,image/webp"
                                                   class="mt-1 w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-600">
                                        </div>
                                        <button type="button" onclick="removeStep(this)"
                                                class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                                    </div>
                                </div>
                            @empty
                                <div class="step-row border border-gray-200 rounded-md p-3">
                                    <div class="flex items-start gap-2">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold mt-1 step-num">1</span>
                                        <div class="flex-1">
                                            <textarea name="steps[0][description]" rows="2" placeholder="手順の説明"
                                                      class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                            <input type="file" name="step_images[0]" accept="image/jpeg,image/png,image/webp"
                                                   class="mt-1 w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-600">
                                        </div>
                                        <button type="button" onclick="removeStep(this)"
                                                class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" onclick="addStep()"
                                class="mt-2 text-sm text-indigo-600 hover:underline">+ 手順を追加</button>
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
                        <a href="{{ route('recipes.show', $recipe) }}" class="text-sm text-gray-500 hover:underline">キャンセル</a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            更新する
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<script>
let ingredientIndex = {{ $recipe->ingredients->count() ?: 1 }};
let stepIndex = {{ $recipe->steps->count() ?: 1 }};

function addIngredient() {
    const i = ingredientIndex++;
    const container = document.getElementById('ingredients-container');
    const row = document.createElement('div');
    row.className = 'ingredient-row flex gap-2';
    row.innerHTML = `
        <input type="text" name="ingredients[${i}][name]" placeholder="食材名"
               class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
        <input type="text" name="ingredients[${i}][quantity]" placeholder="分量"
               class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
        <input type="text" name="ingredients[${i}][unit]" placeholder="単位"
               class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
        <button type="button" onclick="this.closest('.ingredient-row').remove()"
                class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
    `;
    container.appendChild(row);
}

function addStep() {
    const i = stepIndex++;
    const container = document.getElementById('steps-container');
    const num = container.querySelectorAll('.step-row').length + 1;
    const row = document.createElement('div');
    row.className = 'step-row border border-gray-200 rounded-md p-3';
    row.innerHTML = `
        <div class="flex items-start gap-2">
            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold mt-1 step-num">${num}</span>
            <div class="flex-1">
                <textarea name="steps[${i}][description]" rows="2" placeholder="手順の説明"
                          class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                <input type="file" name="step_images[${i}]" accept="image/jpeg,image/png,image/webp"
                       class="mt-1 w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-600">
            </div>
            <button type="button" onclick="removeStep(this)"
                    class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
        </div>
    `;
    container.appendChild(row);
}

function removeStep(btn) {
    btn.closest('.step-row').remove();
    document.querySelectorAll('#steps-container .step-num').forEach((el, i) => {
        el.textContent = i + 1;
    });
}
</script>
</x-app-layout>
