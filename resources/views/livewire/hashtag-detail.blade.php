<div class="space-y-3">
    <div class="card-elevation p-3 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-[var(--accent)] flex items-center justify-center text-white">
            <span class="material-symbols-outlined text-xl">tag</span>
        </div>
        <div>
            <h1 class="font-display font-bold text-lg text-[var(--text-primary)]">#{{ $hashtag->tag }}</h1>
            <p class="text-xs text-[var(--text-secondary)]">{{ $hashtag->posts_count ?? $posts->total() }} postingan</p>
        </div>
    </div>

    @forelse ($posts as $post)
        <livewire:post-item :post="$post" :key="'hashtag-'.$post->id" />
    @empty
        <div class="card-elevation p-8 text-center space-y-3">
            <p class="text-sm text-[var(--text-secondary)]">Belum ada postingan dengan hashtag ini.</p>
        </div>
    @endforelse

    @if ($posts->hasPages())
        <div class="py-2">{{ $posts->links() }}</div>
    @endif
</div>
