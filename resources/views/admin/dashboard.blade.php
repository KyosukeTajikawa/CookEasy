<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('admin.dashboard_title') }}</h2>
    </x-slot>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-sm text-gray-500 mb-1">{{ __('admin.pending_recipes') }}</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-sm text-gray-500 mb-1">{{ __('admin.published_recipes') }}</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['published'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-sm text-gray-500 mb-1">{{ __('admin.total_users') }}</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <p class="text-sm text-gray-500 mb-1">{{ __('admin.total_reviews') }}</p>
            <p class="text-3xl font-bold text-gray-700">{{ $stats['reviews'] }}</p>
        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('admin.quick_links') }}</h3>
            <ul class="space-y-2 text-sm">
                <li>
                    <a href="{{ route('admin.recipes.index') }}"
                       class="text-indigo-600 hover:underline">{{ __('admin.check_pending') }}</a>
                </li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}"
                       class="text-indigo-600 hover:underline">{{ __('admin.check_reviews') }}</a>
                </li>
                <li>
                    <a href="{{ route('admin.recipes.create') }}"
                       class="text-indigo-600 hover:underline">{{ __('admin.create_recipe') }}</a>
                </li>
            </ul>
        </div>

    </div>
</x-admin-layout>
