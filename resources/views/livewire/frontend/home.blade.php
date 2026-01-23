<div>
    {{-- Hero Section --}}
    @if($featuredPost)
        <section class="container mx-auto px-4 mb-12">
            <div class="relative rounded-xl overflow-hidden shadow-2xl group">
                @if($featuredPost->featured_image)
                    <img src="{{ asset('storage/images/posts/' . $featuredPost->featured_image) }}"
                        alt="{{ $featuredPost->title }}"
                        class="w-full h-[500px] object-cover transition-transform duration-700 group-hover:scale-105">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 p-8 md:p-12 text-white max-w-3xl">
                    @if($featuredPost->category)
                        <span
                            class="inline-block px-3 py-1 bg-indigo-600 text-xs font-bold uppercase tracking-wider rounded-full mb-4">{{ $featuredPost->category->name }}</span>
                    @endif
                    <h1
                        class="text-3xl md:text-5xl font-bold leading-tight mb-4 group-hover:text-indigo-200 transition-colors">
                        <a href="{{ route('post.single', $featuredPost->slug) }}">{{ $featuredPost->title }}</a>
                    </h1>
                    <p class="text-gray-300 mb-6 line-clamp-2 md:text-lg">
                        {{ Str::limit(strip_tags($featuredPost->content), 150) }}
                    </p>
                    <div class="flex items-center space-x-4">
                        {{-- <img src="https://ui-avatars.com/api/?name={{ $featuredPost->user->name }}"
                            class="w-10 h-10 rounded-full border-2 border-white"> --}}
                        <div>
                            <p class="font-semibold">{{ $featuredPost->user ? $featuredPost->user->name : 'Admin' }}</p>
                            <p class="text-gray-400 text-sm">
                                {{ $featuredPost->published_at ? $featuredPost->published_at->format('M d, Y') : '' }}
                                &bull; {{ $featuredPost->views }} Views
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Recent Posts Grid --}}
    <section class="container mx-auto px-4 mb-16">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-3xl font-bold text-gray-900 border-l-4 border-indigo-600 pl-4">Latest Stories</h2>
            {{-- <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-800">View All &rarr;</a> --}}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($recentPosts as $post)
                <article
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                    <a href="{{ route('post.single', $post->slug) }}" class="block overflow-hidden relative">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/images/posts/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                class="w-full h-56 object-cover transition-transform duration-500 hover:scale-110">
                        @else
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                        @if($post->category)
                            <div
                                class="absolute top-4 left-4 bg-white px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-sm uppercase">
                                {{ $post->category->name }}
                            </div>
                        @endif
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3
                            class="text-xl font-bold text-gray-900 mb-3 hover:text-indigo-600 transition-colors leading-tight">
                            <a href="{{ route('post.single', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3 flex-1 text-sm leading-relaxed">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <span>{{ $post->published_at ? $post->published_at->format('M d') : '' }}</span>
                                <span class="mx-2">&bull;</span>
                                <span>{{ $post->user ? $post->user->name : 'Admin' }}</span>
                                <span class="mx-2">&bull;</span>
                                <span>{{ $post->views }} Views</span>
                            </div>
                            {{-- <button class="text-indigo-600 hover:text-indigo-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                                    </path>
                                </svg>
                            </button> --}}
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500 text-lg">No posts available yet.</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="container mx-auto px-4 mb-12">
        {{ $recentPosts->links() }}
    </div>

    {{-- Newsletter CTA --}}
    <section class="bg-indigo-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Stay in the Loop</h2>
            <p class="text-indigo-200 mb-8 max-w-2xl mx-auto">Get the latest posts delivered right to your inbox. No
                spam, just great content.</p>
            <form class="max-w-md mx-auto flex">
                <input type="email" placeholder="Enter your email address"
                    class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <button
                    class="bg-indigo-800 px-6 py-3 rounded-r-lg font-bold hover:bg-indigo-900 transition">Subscribe</button>
            </form>
        </div>
    </section>
</div>