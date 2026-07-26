<div class="space-y-4">
    <div class="card-elevation p-4 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-[var(--text-primary)]">Notifikasi</h2>
            <p class="text-xs text-[var(--text-secondary)]">Aktivitas terbaru tentang postingan dan pertemanan Anda.</p>
        </div>
        <button wire:click="markAllAsRead" class="px-3 py-1.5 bg-[var(--surface-hover)] hover:bg-[var(--card-border)] text-[var(--accent)] text-xs font-bold rounded-lg transition">
            Tandai Semua Dibaca
        </button>
    </div>

    <div class="card-elevation p-4 space-y-2">
        @forelse ($notifications as $notif)
            <div 
                wire:click="markAsRead({{ $notif->id }})" 
                class="p-3 rounded-xl flex items-start gap-3 transition cursor-pointer {{ $notif->read_at ? 'bg-white hover:bg-[var(--bg-page)]' : 'bg-[var(--accent)]/5 border-l-4 border-l-[var(--accent)]' }}"
            >
                <img src="{{ $notif->sender->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0 mt-0.5" />
                <div class="flex-1 space-y-0.5">
                    <p class="text-xs text-[var(--text-primary)]">
                        <a href="{{ route('profile.show', $notif->sender->username ?? $notif->sender->id) }}" class="font-bold hover:underline">
                            {{ $notif->sender->name }}
                        </a>
                        <span>{{ $notif->message }}</span>
                    </p>
                    <p class="text-[11px] text-[var(--text-secondary)]">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <div class="py-8 text-center space-y-2">
                <span class="material-symbols-outlined text-4xl text-[var(--text-secondary)]">notifications_off</span>
                <p class="text-xs text-[var(--text-secondary)]">Belum ada notifikasi baru.</p>
            </div>
        @endforelse
    </div>
</div>

