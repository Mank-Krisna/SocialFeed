<div class="space-y-6">
    <!-- Header Title & Create Button -->
    <div class="card-elevation p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-[#1a1c1f] dark:text-[#e2e2e6]">Grup Diskusi &amp; Komunitas</h2>
            <p class="text-xs text-[#727785] dark:text-[#9ca3af]">Temukan komunitas favorit Anda atau buat grup sendiri.</p>
        </div>
        <button 
            wire:click="toggleCreateModal" 
            class="px-4 py-2 bg-[#0058bc] hover:bg-[#004493] text-white text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5 shrink-0"
        >
            <span class="material-symbols-outlined text-base">add_circle</span>
            <span>Buat Grup Baru</span>
        </button>
    </div>

    <!-- My Groups Section -->
    <div class="card-elevation p-5 space-y-4">
        <h3 class="font-bold text-sm text-[#1a1c1f] dark:text-[#e2e2e6] flex items-center gap-2">
            <span class="material-symbols-outlined text-[#0058bc]">groups</span>
            <span>Grup Yang Saya Ikuti ({{ $myGroups->count() }})</span>
        </h3>

        @if ($myGroups->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($myGroups as $group)
                    <div class="p-4 border border-[#e2e2e6] dark:border-[#2d2f34] rounded-xl flex flex-col justify-between space-y-3 bg-white dark:bg-[var(--card-bg)] hover:border-[#0058bc] transition">
                        <div class="flex items-start gap-3">
                            <img src="{{ $group->photo_url }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-[#e2e2e6]" />
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('groups.show', $group->slug) }}" wire:navigate class="font-bold text-sm text-[#1a1c1f] truncate block hover:underline">
                                    {{ $group->name }}
                                </a>
                                <div class="flex flex-wrap items-center gap-1.5 text-[11px] text-[#727785] mt-1">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold leading-tight inline-flex items-center shrink-0 {{ $group->type === 'public' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($group->type) }}
                                    </span>
                                    <span class="text-[#c1c6d6] shrink-0">&bull;</span>
                                    <span class="shrink-0">{{ $group->members_count }} Anggota</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-[#414754] line-clamp-2 leading-relaxed">{{ $group->description ?: 'Belum ada deskripsi.' }}</p>

                        <div class="pt-2.5 border-t border-[#f3f3f7] flex items-center justify-between gap-2 mt-auto">
                            <a href="{{ route('groups.show', $group->slug) }}" wire:navigate class="text-xs font-bold text-[#0058bc] hover:underline shrink-0">
                                Buka Grup &rarr;
                            </a>
                            <button wire:click="leaveGroup({{ $group->id }})" wire:confirm="Keluar dari grup {{ $group->name }}?" class="text-[11px] font-semibold text-red-600 hover:underline shrink-0">
                                Keluar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[#727785] py-2">Anda belum bergabung dalam grup manapun.</p>
        @endif
    </div>

    <!-- Discover Groups Section -->
    <div class="card-elevation p-5 space-y-4">
        <h3 class="font-bold text-sm text-[#1a1c1f] flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">explore</span>
            <span>Jelajahi Grup Publik</span>
        </h3>

        @if ($discoverGroups->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($discoverGroups as $group)
                    <div class="p-4 border border-[#e2e2e6] rounded-xl flex flex-col justify-between space-y-3 bg-white hover:border-[#0058bc] transition">
                        <div class="flex items-start gap-3">
                            <img src="{{ $group->photo_url }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-[#e2e2e6]" />
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('groups.show', $group->slug) }}" wire:navigate class="font-bold text-sm text-[#1a1c1f] truncate block hover:underline">
                                    {{ $group->name }}
                                </a>
                                <div class="flex flex-wrap items-center gap-1.5 text-[11px] text-[#727785] mt-1">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold leading-tight inline-flex items-center shrink-0 {{ $group->type === 'public' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($group->type) }}
                                    </span>
                                    <span class="text-[#c1c6d6] shrink-0">&bull;</span>
                                    <span class="shrink-0">{{ $group->members_count }} Anggota</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-[#414754] line-clamp-2 leading-relaxed">{{ $group->description ?: 'Belum ada deskripsi.' }}</p>

                        <div class="pt-2.5 border-t border-[#f3f3f7] flex items-center justify-between gap-2 mt-auto">
                            <a href="{{ route('groups.show', $group->slug) }}" wire:navigate class="text-xs font-semibold text-[#727785] hover:underline shrink-0">
                                Lihat Detail
                            </a>
                            <button wire:click="joinGroup({{ $group->id }})" class="px-3 py-1 bg-[#0058bc] hover:bg-[#004493] text-white text-xs font-bold rounded-lg transition shrink-0">
                                + Gabung
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-[#727785] py-2">Tidak ada rekomendasi grup publik baru saat ini.</p>
        @endif
    </div>

    <!-- Create Group Modal -->
    <div x-cloak x-show="$wire.showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div x-show="$wire.showCreateModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="card-elevation max-w-md w-full p-4 space-y-4 relative">
                <div class="flex items-center justify-between border-b border-[#e2e2e6] pb-3">
                    <h3 class="font-bold text-base text-[#1a1c1f]">Buat Grup Baru</h3>
                    <button wire:click="toggleCreateModal" class="text-[#727785] hover:text-[#1a1c1f]">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="createGroup" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1a1c1f] mb-1">Nama Grup</label>
                        <input type="text" wire:model="name" placeholder="Misal: Komunitas Laravel Indonesia" class="w-full px-3 py-2 bg-[#f3f3f7] text-xs rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] outline-none" />
                        @error('name') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1a1c1f] mb-1">Deskripsi Grup</label>
                        <textarea wire:model="description" rows="3" placeholder="Tuliskan tujuan & topik bahasan grup..." class="w-full px-3 py-2 bg-[#f3f3f7] text-xs rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] outline-none resize-none"></textarea>
                        @error('description') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1a1c1f] mb-1">Tipe Akses</label>
                        <select wire:model="type" class="w-full px-3 py-2 bg-[#f3f3f7] text-xs rounded-xl border border-[#e2e2e6] focus:border-[#0058bc] outline-none">
                            <option value="public">Publik (Siapapun bisa melihat &amp; bergabung)</option>
                            <option value="private">Privat (Hanya anggota yang bisa melihat postingan)</option>
                        </select>
                        @error('type') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1a1c1f] mb-1">Foto Sampul (Opsional)</label>
                        <input type="file" wire:model="photo" accept="image/*" class="w-full text-xs text-[#727785]" />
                        @error('photo') <span class="text-[11px] text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#e2e2e6]">
                        <button type="button" wire:click="toggleCreateModal" class="px-4 py-2 bg-[#e2e2e6] text-[#414754] text-xs font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-[#0058bc] text-white text-xs font-bold rounded-xl shadow-sm">Buat Grup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
