<div class="space-y-6">
    <!-- Group Cover & Header Card -->
    <div class="card-elevation overflow-hidden">
        <div class="h-36 sm:h-48 bg-gradient-to-r from-[var(--accent)] to-[var(--gold)] flex items-center justify-center relative">
            <span class="material-symbols-outlined text-7xl text-white/20">groups</span>
        </div>

        <div class="p-4 relative pt-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-12 mb-4">
                <div class="flex items-end gap-4">
                    <img src="{{ $group->photo_url }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-white dark:border-[var(--text-primary)] shadow-md bg-white dark:bg-[var(--text-primary)] shrink-0" />
                    <div>
                        <h1 class="text-2xl font-black text-[var(--text-primary)] dark:text-[var(--card-border)]">{{ $group->name }}</h1>
                        <div class="flex items-center gap-2 text-xs text-[var(--text-secondary)] dark:text-[var(--text-secondary)] mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $group->type === 'public' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($group->type) }} Group
                            </span>
                            <span>·</span>
                            <span>{{ $group->members()->count() }} Anggota</span>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 w-full sm:w-auto">
                    @if ($isMember)
                        <button wire:click="leaveGroup" wire:confirm="Keluar dari grup {{ $group->name }}?" class="w-full sm:w-auto px-5 py-2.5 bg-[var(--card-border)] hover:bg-red-100 hover:text-red-600 text-[var(--text-variant)] text-xs font-bold rounded-xl transition">
                            Keluar Grup
                        </button>
                    @else
                        <button wire:click="joinGroup" class="w-full sm:w-auto px-4 py-2.5 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-xl transition shadow-sm">
                            + Bergabung Ke Grup
                        </button>
                    @endif
                </div>
            </div>

            @if ($group->description)
                <p class="text-sm text-[var(--text-variant)] leading-relaxed pt-2 border-t border-[var(--surface-hover)]">
                    {{ $group->description }}
                </p>
            @endif
        </div>
    </div>

    <!-- Group Content Area -->
    @if ($canViewPosts)
        <div class="space-y-6">
            <!-- Create Post Box for Group Members -->
            @if ($isMember)
                <livewire:create-post :groupId="$group->id" :key="'group-create-post-'.$group->id" />
            @else
                <div class="card-elevation p-4 text-center bg-[#f0f4ff] dark:bg-[#1a2332] border border-[#bfdbfe] dark:border-[var(--accent-hover)] text-xs text-[var(--accent)] font-semibold">
                    Bergabung dengan grup ini untuk ikut berdiskusi dan membuat postingan!
                </div>
            @endif

            <!-- Group Posts Stream -->
            <div class="space-y-4">
                @forelse ($posts as $post)
                    <livewire:post-item :post="$post" :key="'group-post-'.$post->id" />
                @empty
                    <div class="card-elevation p-8 text-center space-y-2">
                        <span class="material-symbols-outlined text-4xl text-[var(--text-secondary)]">dynamic_feed</span>
                        <h3 class="font-bold text-base text-[var(--text-primary)]">Belum ada postingan di grup ini</h3>
                        <p class="text-xs text-[var(--text-secondary)]">Jadilah yang pertama untuk membagikan diskusi di grup ini!</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <div class="card-elevation p-12 text-center space-y-3">
            <span class="material-symbols-outlined text-5xl text-[var(--text-secondary)]">lock</span>
            <h3 class="font-bold text-lg text-[var(--text-primary)]">Grup Privat</h3>
            <p class="text-xs text-[var(--text-secondary)] max-w-sm mx-auto">
                Konten dan postingan di dalam grup ini hanya dapat dilihat oleh anggota grup yang sudah terverifikasi.
            </p>
        </div>
    @endif
</div>

