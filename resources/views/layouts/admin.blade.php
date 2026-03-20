<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>管理画面 | {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">

            {{-- サイドナビ --}}
            <aside class="w-56 bg-gray-900 text-gray-100 flex flex-col flex-shrink-0">
                <div class="px-6 py-5 border-b border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold tracking-wide hover:text-white">
                        CookEasy 管理
                    </a>
                </div>
                <nav class="flex-1 px-4 py-4 space-y-1 text-sm">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800' }}">
                        ダッシュボード
                    </a>
                    <a href="{{ route('admin.recipes.index') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.recipes.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800' }}">
                        レシピ管理
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800' }}">
                        レビュー管理
                    </a>
                </nav>
                <div class="px-4 py-4 border-t border-gray-700 text-xs">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white">ログアウト</button>
                    </form>
                </div>
            </aside>

            {{-- メインコンテンツ --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                @isset($header)
                    <header class="bg-white shadow-sm">
                        <div class="px-6 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>

        </div>
    </body>
</html>
