<div class="container mx-auto px-4 max-w-4xl">
    <article class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if($post->featured_image)
            <div class="h-[400px] w-full overflow-hidden relative">
                <img src="{{ asset('storage/images/posts/' . $post->featured_image) }}" alt="{{ $post->title }}"
                    class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/70 to-transparent"></div>
            </div>
        @endif

        <div class="p-6 md:p-12 {{ !$post->featured_image ? 'pt-12' : '' }}">
            <header class="mb-8 text-center md:text-left">
                @if($post->category)
                    <a href="{{ route('category', $post->category->slug) }}"
                        class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 font-semibold rounded-full text-sm mb-4 hover:bg-indigo-100 transition">
                        {{ $post->category->name }}
                    </a>
                @endif

                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">{{ $post->title }}</h1>

                <div
                    class="flex items-center justify-center md:justify-start space-x-4 text-gray-500 border-b border-gray-100 pb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $post->user ? $post->user->name : 'Admin' }}</span>
                    </div>
                    <span>&bull;</span>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : '' }}</span>
                    </div>
                    <span>&bull;</span>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        <span>{{ $post->views }} Views</span>
                    </div>
                </div>
            </header>

            <div class="prose prose-lg prose-indigo max-w-none mb-12 text-gray-700 leading-relaxed">
                {!! $post->content !!}
            </div>

            @if($post->tags->count() > 0)
                <div class="border-t border-gray-100 pt-8 mt-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span
                                class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-sm font-medium hover:bg-gray-200 cursor-pointer">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    {{-- Comments Section --}}
    @if($post->comments_allowed)
        <livewire:frontend.post-comments :post="$post" />
    @endif

    <div class="mt-8 text-center mb-16">
        <a href="/" class="inline-flex items-center font-medium text-indigo-600 hover:text-indigo-800 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Home
        </a>
    </div>
</div>