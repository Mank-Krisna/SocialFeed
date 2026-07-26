<div class="space-y-3">
    <div class="card-elevation p-3">
        <h1 class="font-display font-bold text-lg text-[var(--text-primary)]">Jelajahi Hashtag</h1>
        <p class="text-xs text-[var(--text-secondary)]">Topik populer di SocialFeed</p>
    </div>

    @if ($trending->isEmpty())
        <div class="card-elevation p-8 text-center space-y-3">
            <div class="w-16 h-16 mx-auto rounded-full bg-[var(--accent-container)] flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-[var(--accent)]">tag</span>
            </div>
            <h3 class="font-display font-bold text-lg text-[var(--text-primary)]">Belum ada hashtag</h3>
            <p class="text-sm text-[var(--text-secondary)] max-w-xs mx-auto">
                Hashtag akan muncul ketika pengguna menambahkan #tag di postingan mereka.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach ($trending as $tag)
                <a href="{{ route('hashtags.show', $tag->name) }}" wire:navigate class="card-elevation p-4 hover:shadow-md transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[var(--accent-container)] flex items-center justify-center group-hover:bg-[var(--accent)] group-hover:text-white transition">
                            <span class="material-symbols-outlined text-xl">tag</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-[var(--text-primary)]">#{{ $tag->name }}</p>
                            <p class="text-xs text-[var(--text-secondary)]">{{ $tag->posts_count }} postingan</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
