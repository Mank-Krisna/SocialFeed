<div class="space-y-6">
    <div class="card-elevation p-4 space-y-1">
        <h2 class="text-xl font-bold text-[#1a1c1f]">Daftar Teman &amp; Permintaan</h2>
        <p class="text-xs text-[#727785]">Kelola teman dan hubungan koneksi Anda di SocialFeed.</p>
    </div>

    <!-- Incoming Friend Requests -->
    @if ($incomingRequests->count() > 0)
        <div class="card-elevation p-4 space-y-3 border-l-4 border-l-[#0058bc]">
            <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
                <span class="material-symbols-outlined text-[#0058bc]">person_add</span>
                <span>Permintaan Masuk ({{ $incomingRequests->count() }})</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($incomingRequests as $req)
                    <div class="p-3 bg-[#f3f3f7] rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $req->sender->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $req->sender->username ?? $req->sender->id) }}" class="font-bold text-xs text-[#1a1c1f] truncate block hover:underline">
                                    {{ $req->sender->name }}
                                </a>
                                <p class="text-[11px] text-[#727785] truncate">{{ '@' . e($req->sender->username_display) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="acceptFriendRequest({{ $req->id }})" class="px-3 py-1 bg-[#0058bc] text-white text-xs font-bold rounded-lg hover:bg-[#004493] transition">
                                Terima
                            </button>
                            <button wire:click="rejectFriendRequest({{ $req->id }})" class="px-2.5 py-1 bg-[#e2e2e6] text-[#414754] text-xs font-semibold rounded-lg hover:bg-red-100 hover:text-red-600 transition">
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
        <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">group</span>
            <span>Teman Saya ({{ count($friends) }})</span>
        </h3>
        @if (count($friends) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($friends as $item)
                    <div class="p-3 border border-[#e2e2e6] rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $item['user']->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $item['user']->username ?? $item['user']->id) }}" wire:navigate class="font-bold text-xs text-[#1a1c1f] truncate block hover:underline">
                                    {{ $item['user']->name }}
                                </a>
                                <p class="text-[11px] text-[#727785] truncate">{{ '@' . e($item['user']->username_display) }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="unfriend({{ $item['friendship_id'] }})" 
                            wire:confirm="Hapus {{ $item['user']->name }} dari daftar teman?"
                            class="shrink-0 p-1.5 rounded-lg text-[#727785] hover:text-red-600 hover:bg-red-50 transition"
                            title="Hapus Teman"
                        >
                            <span class="material-symbols-outlined text-base">person_remove</span>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[#727785] py-2">Anda belum memiliki teman di SocialFeed.</p>
        @endif
    </div>

    <!-- Suggested Connections -->
    <div class="card-elevation p-4 space-y-3">
        <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
            <span class="material-symbols-outlined text-[#0058bc]">explore</span>
            <span>Saran Orang Yang Mungkin Anda Kenal</span>
        </h3>
        @if ($suggestedUsers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($suggestedUsers as $sug)
                    <div class="p-3 border border-[#e2e2e6] rounded-xl flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $sug->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <a href="{{ route('profile.show', $sug->username ?? $sug->id) }}" wire:navigate class="font-bold text-xs text-[#1a1c1f] truncate block hover:underline">
                                    {{ $sug->name }}
                                </a>
                                <p class="text-[11px] text-[#727785] truncate">{{ '@' . e($sug->username_display) }}</p>
                            </div>
                        </div>
                        <button wire:click="sendFriendRequest({{ $sug->id }})" class="px-2.5 py-1 bg-[#0058bc]/10 hover:bg-[#0058bc] hover:text-white text-[#0058bc] text-xs font-bold rounded-lg transition shrink-0">
                            + Tambah
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[#727785] py-2">Semua pengguna sudah menjadi teman Anda.</p>
        @endif
    </div>
</div>
