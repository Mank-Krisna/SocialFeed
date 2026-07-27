<div class="relative" x-data="{ open: @entangle('open') }" @click.away="$wire.close()">
    <button wire:click="toggle" class="relative p-2 rounded-full hover:bg-[#f3f3f7] transition" aria-label="Notifikasi">
        <span class="material-symbols-outlined text-[22px] text-[#727785]" aria-hidden="true">notifications</span>
        @if($this->unread_count > 0)
            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-[#ff4444] text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                {{ $this->unread_count > 9 ? '9+' : $this->unread_count }}
            </span>
        @endif
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-[#e8e8ed] z-50 overflow-hidden" style="display: none;">
        <div class="p-3 border-b border-[#f0f0f5] flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#1a1c1f]">Notifikasi</h3>
            @if($this->unread_count > 0)
                <button wire:click="markAllAsRead" class="text-[11px] text-[#0058bc] font-semibold hover:underline">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($this->notifications as $notif)
                <a href="{{ $notif->link ?? '#' }}" wire:click="markAsRead({{ $notif->id }})" class="flex items-start gap-2.5 px-3 py-2.5 hover:bg-[#f9f9fd] transition {{ $notif->read_at ? '' : 'bg-[#0058bc]/[0.03]' }}">
                    <img src="{{ $notif->sender->avatar_url }}" alt="{{ $notif->sender->name }}" width="36" height="36" class="w-9 h-9 rounded-full object-cover shrink-0 mt-0.5" />
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-[#1a1c1f] leading-relaxed">
                            <span class="font-bold">{{ $notif->sender->name }}</span>
                            {{ $notif->message }}
                        </p>
                        <p class="text-[10px] text-[#727785] mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notif->read_at)
                        <span class="w-2 h-2 bg-[#0058bc] rounded-full shrink-0 mt-1.5"></span>
                    @endif
                </a>
            @empty
                <div class="py-8 text-center">
                    <span class="material-symbols-outlined text-3xl text-[#d0d0d8]" aria-hidden="true">notifications_off</span>
                    <p class="text-xs text-[#727785] mt-1">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <a href="{{ route('notifications') }}" class="block text-center py-2.5 text-xs font-semibold text-[#0058bc] border-t border-[#f0f0f5] hover:bg-[#f9f9fd] transition">Lihat Semua</a>
    </div>
</div>
