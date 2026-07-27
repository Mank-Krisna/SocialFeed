<div class="space-y-3">
    <div class="card-elevation p-3">
        <h1 class="font-display font-bold text-lg text-[var(--text-primary)]">Bookmark</h1>
        <p class="text-xs text-[var(--text-secondary)]">Postingan yang Anda simpan</p>
    </div>

    @forelse ($bookmarks as $post)
        <livewire:post-item :post="$post" :key="'bookmark-'.$post->id" />
    @empty
        <div class="card-elevation p-8 text-center space-y-3">
            <div class="w-16 h-16 mx-auto rounded-full bg-[var(--accent-container)] flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-[var(--accent)]">bookmark</span>
            </div>
            <h3 class="font-display font-bold text-lg text-[var(--text-primary)]">Belum ada bookmark</h3>
            <p class="text-sm text-[var(--text-secondary)] max-w-xs mx-auto">
                Simpan postingan menarik dengan menekan ikon bookmark untuk dibaca nanti.
            </p>
        </div>
    @endforelse

    @if ($bookmarks->hasPages())
        <div class="py-2">{{ $bookmarks->links() }}</div>
    @endif
</div>
