<div class="container mx-auto px-4">
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Search Results: "{{ $q }}"</h1>
        <div class="w-16 h-1 bg-indigo-600 mt-6 mx-auto md:mx-0"></div>
    </div>

    @if(count($posts) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <article
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                    <a href="{{ route('post.single', $post->slug) }}" class="block overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/images/posts/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                class="w-full h-56 object-cover transition-transform duration-500 hover:scale-110">
                        @else
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-indigo-600 transition-colors leading-tight">
                            <a href="{{ route('post.single', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed flex-1">
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
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    @else
        <div class="py-20 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-900">No results found</h3>
            <p class="text-gray-500 mt-2">Try adjusting your search terms.</p>
        </div>
    @endif
</div>