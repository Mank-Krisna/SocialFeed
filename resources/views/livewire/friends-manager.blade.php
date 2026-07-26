<div class="space-y-6">
    <div class="card-elevation p-4 space-y-1">
        <h2 class="text-xl font-bold text-[var(--text-primary)]">Daftar Teman &amp; Permintaan</h2>
        <p class="text-xs text-[var(--text-secondary)]">Kelola teman dan hubungan koneksi Anda di SocialFeed.</p>
    </div>

    <!-- Incoming Friend Requests -->
    @if ($incomingRequests->count() > 0)
        <div class="card-elevation p-4 space-y-3 border-l-4 border-l-[var(--accent)]">
            <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
                <span class="material-symbols-outlined text-[var(--accent)]">person_add</span>
                <span>Permintaan Masuk ({{ $incomingRequests->count() }})</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($incomingRequests as $req)
                    <div class="p-3 bg-[var(--surface-hover)] rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $req->sender->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $req->sender->username ?? $req->sender->id) }}" class="font-bold text-xs text-[var(--text-primary)] truncate block hover:underline">
                                    {{ $req->sender->name }}
                                </a>
                                <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ '@' . e($req->sender->username_display) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="acceptFriendRequest({{ $req->id }})" class="px-3 py-1 bg-[var(--accent)] text-white text-xs font-bold rounded-lg hover:bg-[var(--accent-hover)] transition">
                                Terima
                            </button>
                            <button wire:click="rejectFriendRequest({{ $req->id }})" class="px-2.5 py-1 bg-[var(--card-border)] text-[var(--text-variant)] text-xs font-semibold rounded-lg hover:bg-red-100 hover:text-red-600 transition">
                                Tolak
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Accepted Friends List -->
    <div class="card-elevation p-4 space-y-3">
        <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">group</span>
            <span>Teman Saya ({{ count($friends) }})</span>
        </h3>
        @if (count($friends) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($friends as $item)
                    <div class="p-3 border border-[var(--card-border)] rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $item['user']->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $item['user']->username ?? $item['user']->id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] truncate block hover:underline">
                                    {{ $item['user']->name }}
                                </a>
                                <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ '@' . e($item['user']->username_display) }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="unfriend({{ $item['friendship_id'] }})" 
                            wire:confirm="Hapus {{ $item['user']->name }} dari daftar teman?"
                            class="shrink-0 p-1.5 rounded-lg text-[var(--text-secondary)] hover:text-red-600 hover:bg-red-50 transition"
                            title="Hapus Teman"
                        >
                            <span class="material-symbols-outlined text-base">person_remove</span>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[var(--text-secondary)] py-2">Anda belum memiliki teman di SocialFeed.</p>
        @endif
    </div>

    <!-- Suggested Connections -->
    <div class="card-elevation p-4 space-y-3">
        <h3 class="font-bold text-sm text-[var(--text-primary)] flex items-center gap-2">
            <span class="material-symbols-outlined text-[var(--accent)]">explore</span>
            <span>Saran Orang Yang Mungkin Anda Kenal</span>
        </h3>
        @if ($suggestedUsers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($suggestedUsers as $sug)
                    <div class="p-3 border border-[var(--card-border)] rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $sug->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $sug->username ?? $sug->id) }}" wire:navigate class="font-bold text-xs text-[var(--text-primary)] truncate block hover:underline">
                                    {{ $sug->name }}
                                </a>
                                <p class="text-[11px] text-[var(--text-secondary)] truncate">{{ '@' . e($sug->username_display) }}</p>
                            </div>
                        </div>
                        <button wire:click="sendFriendRequest({{ $sug->id }})" class="px-2.5 py-1 bg-[var(--accent)]/10 hover:bg-[var(--accent)] hover:text-white text-[var(--accent)] text-xs font-bold rounded-lg transition shrink-0">
                            + Tambah
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[var(--text-secondary)] py-2">Semua pengguna sudah menjadi teman Anda.</p>
        @endif
    </div>
</div>

