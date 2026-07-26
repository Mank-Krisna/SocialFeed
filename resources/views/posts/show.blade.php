<x-app-layout>
    <div class="space-y-6">
        <!-- Post Card Full -->
        <livewire:post-item :post="$post" :key="'post-detail-'.$post->id" />

        <!-- Full Comments Section -->
        <div class="card-elevation p-4 space-y-3">
            <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                <span aria-hidden="true" class="material-symbols-outlined text-[var(--accent)]">chat_bubble</span>
                <span>Semua Komentar ({{ $post->comments_count ?? $post->comments()->count() }})</span>
            </h3>
            <livewire:comment-section :post="$post" :key="'detail-comments-'.$post->id" />
        </div>
    </div>
</x-app-layout>

