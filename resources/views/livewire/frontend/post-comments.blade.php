<div class="mt-12">
    <h3 class="text-2xl font-bold text-gray-900 mb-6">Comments ({{ $comments->count() }})</h3>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Comment Form --}}
    <div class="bg-gray-50 p-6 rounded-lg mb-8" id="comment-form">
        <form wire:submit.prevent="postComment">
            @if($replyingTo)
                <div class="flex items-center justify-between bg-blue-50 p-2 mb-2 rounded text-sm text-blue-700">
                    <span>Replying to <strong>{{ $replyingToName }}</strong></span>
                    <button type="button" wire:click="cancelReply"
                        class="text-blue-500 hover:text-blue-700 font-bold">&times;</button>
                </div>
            @endif

            @guest
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" wire:model="guest_name" id="guest_name"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                        @error('guest_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" wire:model="guest_email" id="guest_email"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                        @error('guest_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endguest

            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $replyingTo ? 'Write a reply...' : 'Leave a comment' }}
                </label>
                <textarea wire:model="content" id="comment" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"
                    placeholder="Share your thoughts..."></textarea>
                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $replyingTo ? 'Post Reply' : 'Post Comment' }}
                </button>
            </div>
        </form>
    </div>

    {{-- Comments List --}}
    <div class="space-y-6">
        @forelse ($comments as $comment)
            {{-- Comment Item --}}
            <div class="flex space-x-4 p-4 bg-white rounded shadow-sm border border-gray-100">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full"
                        src="{{ $comment->user->picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user ? $comment->user->name : $comment->guest_name) }}"
                        alt="{{ $comment->user->name ?? $comment->guest_name }}">
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? $comment->guest_name }} <span
                                class="font-normal text-xs text-gray-400">({{ $comment->user ? 'Member' : 'Guest' }})</span>
                        </h4>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 text-sm mb-2">{{ $comment->content }}</p>

                    <button
                        wire:click="startReply({{ $comment->id }}, '{{ $comment->user ? $comment->user->name : $comment->guest_name }}')"
                        onclick="document.getElementById('comment-form').scrollIntoView({behavior: 'smooth'})"
                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium focus:outline-none">
                        Reply
                    </button>

                    {{-- Replies --}}
                    @if($comment->replies->count() > 0)
                        <div class="mt-4 space-y-4 pl-4 border-l-2 border-gray-100">
                            @foreach($comment->replies as $reply)
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full"
                                            src="{{ $reply->user->picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user ? $reply->user->name : $reply->guest_name) }}"
                                            alt="{{ $reply->user->name ?? $reply->guest_name }}">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-xs font-bold text-gray-900">
                                                {{ $reply->user->name ?? $reply->guest_name }}</h4>
                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center italic">No comments yet. Be the first to share your thoughts!</p>
        @endforelse
    </div>
</div>