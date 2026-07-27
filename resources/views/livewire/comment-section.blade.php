<div class="mt-3 pt-3 border-t border-[var(--surface-hover)] space-y-3">
    <form wire:submit.prevent="submitComment" class="flex gap-2 items-center">
        <img 
            src="{{ auth()->user()->avatar_url }}" 
            alt="{{ auth()->user()->name }}" 
            width="32" height="32"
            class="w-8 h-8 rounded-full object-cover shrink-0 border border-[var(--card-border)]"
        />
        <input 
            type="text" 
            wire:model.live="body" 
            name="comment"
            aria-label="Tulis komentar"
            placeholder="Tulis komentar…" 
            class="flex-1 px-3 py-1.5 bg-[var(--surface-hover)] focus:bg-[var(--card-bg)] text-xs text-[var(--text-primary)] rounded-full border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition"
        />
        <button 
            type="submit" 
            wire:loading.attr="disabled"
            class="px-3 py-1.5 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-full transition disabled:opacity-50"
        >
            Kirim
        </button>
    </form>

    <div class="space-y-3">
        @forelse ($comments as $comment)
            <div class="flex items-start gap-2.5 group">
                <img 
                    src="{{ $comment->user->avatar_url }}" 
                    alt="{{ $comment->user->name }}" 
                    width="28" height="28"
                    class="w-7 h-7 rounded-full object-cover shrink-0 border border-[var(--card-border)] mt-0.5"
                />
                <div class="flex-1 space-y-1">
                    <div class="bg-[var(--surface-hover)] p-2.5 rounded-2xl inline-block max-w-full">
                        <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] hover:underline">
                            {{ $comment->user->name }}
                        </a>
                        <p class="text-xs text-[var(--text-variant)] whitespace-pre-line mt-0.5">{{ $comment->body }}</p>
                    </div>

                    <div class="flex items-center gap-3 text-[11px] text-[var(--text-secondary)] ps-2">
                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                        <button wire:click="toggleReply({{ $comment->id }})" class="font-semibold hover:text-[var(--accent)] transition">
                            Balas
                        </button>
                    </div>

                    <div x-cloak x-show="$wire.replyToCommentId === {{ $comment->id }}" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        @if ($replyToCommentId === $comment->id)
                        <form wire:submit.prevent="submitReply({{ $comment->id }})" class="flex gap-2 items-center pt-2 ps-2">
                            <input 
                                type="text" 
                                wire:model.live="replyBody" 
                                placeholder="Balas {{ $comment->user->name }}..." 
                                class="flex-1 px-3 py-1 bg-[var(--card-bg)] border border-[var(--accent)] text-xs text-[var(--text-primary)] rounded-full outline-none"
                            />
                            <button 
                                type="submit" 
                                class="px-3 py-1 bg-[var(--accent)] text-white text-xs font-bold rounded-full hover:bg-[var(--accent-hover)]"
                            >
                                Balas
                            </button>
                        </form>
                        @endif
                    </div>

                    @if ($comment->replies->count() > 0)
                        <div class="ps-4 border-s-2 border-[var(--card-border)] mt-2 space-y-2">
                            @foreach ($comment->replies as $reply)
                                <div class="flex items-start gap-2">
                                    <img 
                                        src="{{ $reply->user->avatar_url }}" 
                                        alt="{{ $reply->user->name }}" 
                                        width="24" height="24"
                                        class="w-6 h-6 rounded-full object-cover shrink-0 border border-[var(--card-border)] mt-0.5"
                                    />
                                    <div>
                                        <div class="bg-[var(--surface-hover)] dark:bg-[var(--card-bg)] p-2 rounded-2xl inline-block max-w-full">
                                            <a href="{{ route('profile.show', $reply->user->username ?? $reply->user->id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] hover:underline">
                                                {{ $reply->user->name }}
                                            </a>
                                            <p class="text-xs text-[var(--text-variant)] whitespace-pre-line mt-0.5">{{ $reply->body }}</p>
                                        </div>
                                        <div class="text-[10px] text-[var(--text-secondary)] ps-2 mt-0.5">
                                            {{ $reply->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-xs text-[var(--text-secondary)] text-center py-1">Belum ada komentar.</p>
        @endforelse
    </div>
</div>
