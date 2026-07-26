<div class="space-y-3">
    <!-- Stories -->
    <livewire:story-section />

    <!-- Post Creation Box -->
    <livewire:create-post />

    <!-- Feed Filter Toggle -->
    <div class="card-elevation p-2 flex items-center gap-2">
        <button 
            wire:click="setFilter('all')" 
            class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $filter === 'all' ? 'bg-[var(--accent)] text-white shadow-sm' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}"
        >
            Semua Postingan
        </button>
        <button 
            wire:click="setFilter('friends')" 
            class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $filter === 'friends' ? 'bg-[var(--accent)] text-white shadow-sm' : 'text-[var(--text-variant)] hover:bg-[var(--surface-hover)]' }}"
        >
            Postingan Teman
        </button>
    </div>

    <!-- Posts Feed Stream -->
    <div class="space-y-3">
        {{-- Skeleton loaders shown while feed is loading --}}
        <div wire:loading wire:target="setFilter" class="space-y-3">
            <x-skeleton-post />
            <x-skeleton-post />
            <x-skeleton-post />
        </div>

        <div wire:loading.remove wire:target="setFilter" class="space-y-3 stagger-enter">
            @forelse ($posts as $post)
                <livewire:post-item :post="$post" :key="'post-'.$post->id" />
            @empty
                {{-- Empty state with illustration --}}
                <div class="card-elevation p-8 text-center space-y-3">
                    <div class="w-16 h-16 mx-auto rounded-full bg-[var(--accent-container)] flex items-center justify-center">
                        <span aria-hidden="true" class="material-symbols-outlined text-4xl text-[var(--accent)]" style="font-variation-settings: 'FILL' 1">dynamic_feed</span>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)]">
                            {{ $filter === 'friends' ? 'Feed teman masih kosong' : 'Belum ada postingan' }}
                        </h3>
                        <p class="text-sm text-[var(--text-secondary)] max-w-xs mx-auto leading-relaxed">
                            {{ $filter === 'friends' 
                                ? 'Tambahkan teman untuk melihat postingan mereka di sini. Jelajahi halaman pencarian untuk menemukan pengguna baru!' 
                                : 'Jadilah yang pertama untuk membagikan status, foto, atau cerita hari ini! Klik kotak di atas untuk memulai.' }}
                        </p>
                    </div>
                    @if ($filter === 'friends')
                        <a href="{{ route('search') }}" wire:navigate class="inline-block px-5 py-2 bg-[var(--accent)] text-white text-sm font-bold rounded-lg hover:bg-[var(--accent-hover)] transition shadow-sm">
                            Cari Teman
                        </a>
                    @endif
                </div>
            @endforelse

            {{-- Infinite scroll sentinel --}}
            @if ($hasMore)
                <div 
                    x-intersect="$wire.loadMore()"
                    class="flex justify-center pt-2 pb-4"
                >
                    <div wire:loading wire:target="loadMore" class="flex items-center gap-2 text-sm text-[var(--text-secondary)]">
                        <svg class="animate-spin h-4 w-4 text-[var(--accent)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memuat postingan...</span>
                    </div>
                    <div wire:loading.remove wire:target="loadMore">
                        <button 
                            wire:click="loadMore" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-[var(--surface-hover)] hover:bg-[var(--card-border)] text-[var(--text-variant)] font-semibold text-sm rounded-full transition disabled:opacity-50"
                        >
                            Muat Lainnya
                        </button>
                    </div>
                </div>
            @endif

            {{-- End of feed marker --}}
            @if (!$hasMore && count($posts) > 0)
                <div class="text-center py-3 space-y-1">
                    <span class="material-symbols-outlined text-lg text-[var(--text-secondary)] opacity-30">check_circle</span>
                    <p class="text-xs text-[var(--text-secondary)]">Sudah semua</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Infinite scroll sentinel --}}
    <div id="feed-sentinel" class="w-full h-4"></div>
</div>
