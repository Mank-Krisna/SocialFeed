<div class="card-elevation p-3 space-y-2.5">
    @if ($post->parent)
        <div class="flex items-center gap-1.5 text-xs text-[var(--text-secondary)] font-medium">
            <span aria-hidden="true" class="material-symbols-outlined text-sm">repeat</span>
            <span>Dibagikan ulang oleh {{ $post->user->name }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.show', $post->user->username ?? $post->user->id) }}" wire:navigate>
                <img 
                    src="{{ $post->user->avatar_url }}" 
                    alt="{{ $post->user->name }}" 
                    width="40" height="40"
                    class="w-10 h-10 rounded-full object-cover border border-[var(--card-border)] hover:opacity-90 transition"
                />
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->username ?? $post->user->id) }}" wire:navigate class="font-display font-semibold text-sm text-[var(--text-primary)] hover:underline block tracking-tight">
                    {{ $post->user->name }}
                </a>
                <p class="text-xs text-[var(--text-secondary)] flex items-center gap-1.5">
                    <a href="{{ route('posts.show', $post->id) }}" wire:navigate class="hover:underline">
                        {{ $post->created_at->diffForHumans() }}
                    </a>
                    <span class="w-1 h-1 rounded-full bg-[var(--accent)] inline-block"></span>
                    <span aria-hidden="true" class="material-symbols-outlined text-[11px] text-[var(--text-secondary)]">public</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1">
            @if ($post->user_id === auth()->id())
                <button 
                    wire:click="deletePost" 
                    wire:confirm="Yakin ingin menghapus postingan ini?"
                    class="p-1 rounded-lg text-[var(--text-secondary)] hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950 transition"
                    title="Hapus Postingan"
                >
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            @endif
        </div>
    </div>

    @if ($post->parent)
        <div class="p-3 bg-[var(--surface-hover)] rounded-xl space-y-1 border-l-2 border-[var(--accent)]">
            <a href="{{ route('profile.show', $post->parent->user->username ?? $post->parent->user_id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] hover:underline">
                {{ $post->parent->user->name }}
            </a>
            <p class="text-xs text-[var(--text-secondary)]">{{ Str::limit($post->parent->body, 200) }}</p>
        </div>
    @endif

    @if ($post->body)
        <div class="text-sm text-[var(--text-primary)] leading-snug font-display tracking-tight [&_a]:text-[var(--accent)] [&_a]:hover:underline [&_a]:font-semibold">
            {!! $post->body_html !!}
        </div>
    @endif

    @if ($post->media && $post->media->count() > 0)
        <div class="grid {{ $post->media->count() === 1 ? 'grid-cols-1' : ($post->media->count() === 2 ? 'grid-cols-2' : 'grid-cols-3') }} gap-2 rounded-xl overflow-hidden">
            @foreach ($post->media as $media)
                @if ($media->isVideo())
                    <div class="col-span-full rounded-xl overflow-hidden bg-black flex items-center justify-center border border-[var(--card-border)] shadow-sm">
                        <video controls preload="metadata" class="w-full max-h-[450px] rounded-xl object-contain bg-black">
                            <source src="{{ $media->url }}">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                @else
                    <a href="{{ $media->url }}" target="_blank" rel="noopener noreferrer" class="block aspect-video bg-[var(--surface-hover)] overflow-hidden rounded-lg hover:opacity-95 transition border border-[var(--card-border)]">
                        <img src="{{ $media->url }}" alt="Media" class="w-full h-full object-cover" />
                    </a>
                @endif
            @endforeach
        </div>
    @endif

    @if ($post->poll)
        <livewire:poll-display :poll="$post->poll" :key="'poll-'.$post->id" />
    @endif

    <div class="flex items-center justify-between text-xs text-[var(--text-secondary)] pt-1.5 border-t border-[var(--surface-hover)]">
        <div class="flex items-center gap-1">
            <span class="w-4 h-4 rounded-full bg-[var(--accent)] text-white flex items-center justify-center text-[10px]">
                <span aria-hidden="true" class="material-symbols-outlined text-[12px] filled">thumb_up</span>
            </span>
            <span class="font-semibold">{{ $likesCount }} Suka</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-semibold">{{ $repostsCount }} Bagikan</span>
            <button wire:click="toggleComments" class="hover:underline font-semibold">
                {{ $commentsCount }} Komentar
            </button>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-0.5 pt-1.5 border-t border-[var(--surface-hover)]">
        <button 
            wire:click="toggleLike" 
            wire:loading.attr="disabled"
            class="py-1.5 rounded-xl flex items-center justify-center gap-1 text-sm font-semibold transition active:scale-90 disabled:opacity-50 {{ $isLiked ? 'text-[var(--accent)] bg-[var(--accent)]/10' : 'text-[var(--text-secondary)] hover:bg-[var(--surface-hover)]' }}"
            x-on:click="$el.querySelector('.material-symbols-outlined').classList.add('animate-like-bounce'); setTimeout(() => $el.querySelector('.material-symbols-outlined').classList.remove('animate-like-bounce'), 350)"
        >
            <span aria-hidden="true" class="material-symbols-outlined text-xl {{ $isLiked ? 'filled' : '' }}">thumb_up</span>
        </button>

        <button 
            wire:click="toggleComments" 
            class="py-1.5 rounded-xl flex items-center justify-center gap-1 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--surface-hover)] active:scale-90 transition"
        >
            <span aria-hidden="true" class="material-symbols-outlined text-xl">chat_bubble</span>
        </button>

        <livewire:repost-button :post="$post" :key="'repost-'.$post->id" />

        <livewire:bookmark-button :post="$post" :key="'bookmark-'.$post->id" />
    </div>

    <div x-cloak x-show="$wire.showComments" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        @if ($showComments)
            <livewire:comment-section :post="$post" :key="'post-comments-'.$post->id" />
        @endif
    </div>
</div>
