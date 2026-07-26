<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#1a1c1f] dark:text-white">Panel Admin</h1>
        <p class="text-sm text-[#727785] dark:text-[#9ca3af]">Kelola pengguna, postingan, dan pengaturan platform</p>
    </div>

    {{-- Tab nav --}}
    <div class="flex gap-1 mb-6 p-1 bg-[#f3f3f7] dark:bg-[#25282e] rounded-xl">
        @foreach (['overview' => 'Ringkasan', 'users' => 'Pengguna', 'posts' => 'Postingan'] as $key => $label)
            <button wire:click="switchTab('{{ $key }}')" class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $tab === $key ? 'bg-white dark:bg-[#1a1c1f] text-[#0058bc] shadow-sm' : 'text-[#414754] dark:text-[#b0b4be] hover:bg-white/50 dark:hover:bg-[#1a1c1f]/50' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($tab === 'overview')
        {{-- Stats grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach ([
                ['label' => 'Pengguna', 'count' => $stats['users'], 'icon' => 'people', 'color' => 'text-[#0058bc] bg-[#f0f4ff] dark:bg-[#1a2332]'],
                ['label' => 'Postingan', 'count' => $stats['posts'], 'icon' => 'article', 'color' => 'text-green-500 bg-green-50 dark:bg-green-500/10'],
                ['label' => 'Grup', 'count' => $stats['groups'], 'icon' => 'groups', 'color' => 'text-purple-500 bg-purple-50 dark:bg-purple-500/10'],
                ['label' => 'Cerita Aktif', 'count' => $stats['stories'], 'icon' => 'stories', 'color' => 'text-pink-500 bg-pink-50 dark:bg-pink-500/10'],
            ] as $card)
                <div class="card-elevation p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm {{ $card['color'] }} p-1.5 rounded-lg">{{ $card['icon'] }}</span>
                    </div>
                    <p class="text-2xl font-bold text-[#1a1c1f] dark:text-white">{{ $card['count'] }}</p>
                    <p class="text-xs text-[#727785] dark:text-[#b0b4be] font-semibold">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Recent users --}}
        <div class="card-elevation p-4 mb-4">
            <h3 class="font-bold text-sm text-[#1a1c1f] dark:text-white mb-3">Pengguna Terbaru</h3>
            <div class="space-y-2">
                @foreach (\App\Models\User::latest()->take(5)->get() as $u)
                    <div class="flex items-center gap-3">
                        <img src="{{ $u->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-[#1a1c1f] dark:text-white truncate">{{ $u->name }}</p>
                            <p class="text-xs text-[#727785] truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-xs text-[#727785]">{{ $u->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($tab === 'users')
        <div class="card-elevation p-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[#727785]">search</span>
                <input wire:model.live.debounce.300ms="userSearch" type="text" placeholder="Cari pengguna..." class="flex-1 bg-transparent border-none outline-none text-sm text-[#1a1c1f] dark:text-white placeholder:text-[#9ca3af]">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#727785] dark:text-[#b0b4be] border-b border-[#ced0d4] dark:border-[#3e4148]">
                            <th class="pb-2 font-semibold">Pengguna</th>
                            <th class="pb-2 font-semibold">Email</th>
                            <th class="pb-2 font-semibold">Bergabung</th>
                            <th class="pb-2 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr class="border-b border-[#f3f3f7] dark:border-[#25282e]">
                                <td class="py-2.5 flex items-center gap-2">
                                    <img src="{{ $u->avatar_url }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                    <span class="font-semibold text-[#1a1c1f] dark:text-white">{{ $u->name }}</span>
                                </td>
                                <td class="py-2.5 text-[#727785]">{{ $u->email }}</td>
                                <td class="py-2.5 text-[#727785]">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="py-2.5">
                                    @if ($u->id !== auth()->id())
                                        @if ($confirmDeleteUserId === $u->id)
                                            <div class="flex items-center gap-1">
                                                <span class="text-xs text-red-500">Yakin?</span>
                                                <button wire:click="deleteUser({{ $u->id }})" class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Ya</button>
                                                <button wire:click="$set('confirmDeleteUserId', null)" class="text-xs px-2 py-1 bg-gray-200 dark:bg-[#33363d] rounded hover:bg-gray-300 dark:hover:bg-[#3e4148] transition">Batal</button>
                                            </div>
                                        @else
                                            <button wire:click="$set('confirmDeleteUserId', {{ $u->id }})" class="text-xs px-2 py-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded transition">Hapus</button>
                                        @endif
                                    @else
                                        <span class="text-xs text-[#727785]">Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="mt-4">{{ $users->links(data: ['scrollTo' => false]) }}</div>
            @endif
        </div>
    @endif

    @if ($tab === 'posts')
        <div class="card-elevation p-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[#727785]">search</span>
                <input wire:model.live.debounce.300ms="postSearch" type="text" placeholder="Cari postingan..." class="flex-1 bg-transparent border-none outline-none text-sm text-[#1a1c1f] dark:text-white placeholder:text-[#9ca3af]">
            </div>
            <div class="space-y-3">
                @foreach ($posts as $p)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-[#f8f9fa] dark:bg-[#25282e]">
                        <img src="{{ $p->user?->avatar_url }}" alt="" class="w-8 h-8 rounded-full mt-0.5 object-cover">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-[#1a1c1f] dark:text-white">{{ $p->user?->name ?? '[Deleted]' }}</span>
                                <span class="text-[10px] text-[#727785]">{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-[#414754] dark:text-[#b0b4be] mt-0.5 line-clamp-2">{!! $p->body_html !!}</p>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[10px] text-[#727785]">{{ $p->likes_count ?? $p->likes->count() ?? 0 }} suka</span>
                                <span class="text-[10px] text-[#727785]">{{ $p->comments_count ?? $p->comments->count() ?? 0 }} komentar</span>
                                @if ($confirmDeletePostId === $p->id)
                                    <div class="flex items-center gap-1 ms-auto">
                                        <span class="text-xs text-red-500">Yakin?</span>
                                        <button wire:click="deletePost({{ $p->id }})" class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Ya</button>
                                        <button wire:click="$set('confirmDeletePostId', null)" class="text-xs px-2 py-1 bg-gray-200 dark:bg-[#33363d] rounded hover:bg-gray-300 dark:hover:bg-[#3e4148] transition">Batal</button>
                                    </div>
                                @else
                                    <button wire:click="$set('confirmDeletePostId', {{ $p->id }})" class="text-xs px-2 py-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded transition ms-auto">Hapus</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($posts->hasPages())
                <div class="mt-4">{{ $posts->links(data: ['scrollTo' => false]) }}</div>
            @endif
        </div>
    @endif
</div>
