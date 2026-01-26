<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $pageTitle ?? 'Blog' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ searchOpen: false }">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center bg-white relative z-20">
            @if(\App\Helpers\Settings::get('site_logo'))
                <a href="/" class="flex items-center">
                    <img src="{{ \App\Helpers\Settings::logo() }}" alt="{{ \App\Helpers\Settings::siteName() }}" class="h-10 max-w-[150px] object-contain">
                </a>
            @else
                <a href="/" class="text-2xl font-bold text-indigo-600">{{ \App\Helpers\Settings::siteName() }}</a>
            @endif

            <div class="hidden md:flex space-x-6 items-center">
                <a href="/" class="text-gray-600 hover:text-indigo-600 font-medium">Home</a>

                <div class="relative group">
                    <button class="text-gray-600 hover:text-indigo-600 font-medium flex items-center">
                        Categories
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        @foreach(\App\Models\Category::all() as $category)
                            <a href="{{ route('category', $category->slug) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium">About</a> --}}
            </div>

            <div class="flex items-center space-x-4">
                <button @click="searchOpen = !searchOpen"
                    class="text-gray-500 hover:text-indigo-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Dashboard</a>
                @endauth
            </div>
        </nav>

        <div x-show="searchOpen" @click.away="searchOpen = false"
            class="absolute top-full left-0 w-full bg-white shadow-md p-4 z-10"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
            style="display: none;">
            <div class="container mx-auto">
                <form action="{{ route('search') }}" method="GET" class="flex">
                    <input type="text" name="q" placeholder="Search for posts..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-r-md hover:bg-indigo-700 transition">Search</button>
                </form>
            </div>
        </div>
    </header>

    <main class="min-h-screen py-8">
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ \App\Helpers\Settings::siteName() }}</h3>
                    <p class="text-gray-400">A place for interesting thoughts and ideas.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Categories</h4>
                    <ul class="space-y-2 text-gray-400">
                        @foreach(\App\Models\Category::take(5)->get() as $cat)
                            <li><a href="{{ route('category', $cat->slug) }}" class="hover:text-white">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Newsletter</h4>
                    <form class="flex">
                        <input type="email" placeholder="Your email"
                            class="px-4 py-2 w-full rounded-l-md text-gray-900 focus:outline-none">
                        <button class="bg-indigo-600 px-4 py-2 rounded-r-md hover:bg-indigo-700">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-500">
                &copy; {{ date('Y') }} {{ \App\Helpers\Settings::siteName() }}. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
