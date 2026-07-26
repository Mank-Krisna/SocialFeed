<div class="card-elevation overflow-hidden flex h-[75vh]" x-data="{ showPanel: 'list' }">
    {{-- Left: Conversation List --}}
    <div class="w-full sm:w-80 lg:w-96 border-r border-[var(--card-border)] dark:border-[var(--card-border)] flex flex-col shrink-0"
         x-show="showPanel === 'list' || !$wire.activeConversationId"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0">
        <div class="p-3 border-b border-[var(--card-border)] dark:border-[var(--card-border)] space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-sm text-[var(--text-primary)] dark:text-[var(--card-border)]">Pesan</h2>
                <span class="text-xs text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">{{ $this->conversations->count() }} percakapan</span>
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[var(--text-secondary)] text-sm">search</span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchQuery" 
                    placeholder="Cari pengguna..."
                    class="w-full pl-8 pr-3 py-1.5 bg-[var(--surface-hover)] dark:bg-[var(--surface-hover)] text-xs text-[var(--text-primary)] dark:text-[var(--card-border)] rounded-full border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition"
                />
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @if (strlen(trim($searchQuery)) >= 1)
                <div class="p-2 space-y-0.5">
                    @forelse ($searchResults as $user)
                        <button 
                            wire:click="openOrCreateConversation({{ $user->id }})" 
                            @click="showPanel = 'chat'"
                            class="w-full p-2.5 rounded-lg flex items-center gap-2.5 hover:bg-[var(--surface-hover)] dark:hover:bg-[var(--surface-hover)] transition text-start"
                        >
                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" />
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-[var(--text-primary)] dark:text-[var(--card-border)] truncate">{{ $user->name }}</p>
                                <p class="text-[11px] text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">@ {{ e($user->username_display) }}</p>
                            </div>
                        </button>
                    @empty
                        <p class="text-xs text-[var(--text-secondary)] text-center py-4">Pengguna tidak ditemukan</p>
                    @endforelse
                </div>
            @else
                <div class="p-2 space-y-0.5">
                    @forelse ($this->conversations as $conv)
                        @php $other = $conv->otherUser(auth()->user()); @endphp
                        @if ($other)
                        <button 
                            wire:click="openConversation({{ $conv->id }})" 
                            @click="showPanel = 'chat'"
                            class="w-full p-2.5 rounded-lg flex items-center gap-2.5 hover:bg-[var(--surface-hover)] dark:hover:bg-[var(--surface-hover)] transition text-start {{ $activeConversationId === $conv->id ? 'bg-[var(--accent)]/5 ring-1 ring-[var(--accent)]/20' : '' }}"
                        >
                            <div class="relative shrink-0">
                                <img src="{{ $other->avatar_url }}" class="w-10 h-10 rounded-full object-cover" />
                                @if ($conv->unread_count > 0)
                                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center">{{ $conv->unread_count > 9 ? '9+' : $conv->unread_count }}</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-[var(--text-primary)] dark:text-[var(--card-border)] truncate">{{ $other->name }}</p>
                                    @if ($conv->messages->first())
                                        <span class="text-[10px] text-[var(--text-secondary)] shrink-0">{{ $conv->messages->first()->created_at->diffForHumans(['short' => true]) }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-[var(--text-secondary)] dark:text-[var(--text-secondary)] truncate {{ $conv->unread_count > 0 ? 'font-semibold' : '' }}">
                                    {{ $conv->messages->first()?->body ?? 'Belum ada pesan' }}
                                </p>
                            </div>
                        </button>
                        @endif
                    @empty
                        <div class="p-4 text-center space-y-1">
                            <span class="material-symbols-outlined text-3xl text-[var(--text-secondary)]">chat</span>
                            <p class="text-xs text-[var(--text-secondary)]">Belum ada percakapan</p>
                            <p class="text-[11px] text-[var(--text-secondary)]">Cari pengguna untuk memulai pesan</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

    {{-- Right: Chat Panel --}}
    <div class="flex-1 flex flex-col {{ $activeConversationId ? '' : 'hidden sm:flex' }}"
         x-show="showPanel === 'chat' || $wire.activeConversationId">
        @if ($activeConversationId)
            @php 
                $conv = $this->conversations->firstWhere('id', $activeConversationId);
                $other = $conv?->otherUser(auth()->user());
            @endphp
            <div class="p-3 border-b border-[var(--card-border)] dark:border-[var(--card-border)] flex items-center gap-2.5">
                <button @click="showPanel = 'list'" class="sm:hidden p-1 rounded-lg text-[var(--text-secondary)] hover:bg-[var(--surface-hover)] transition">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </button>
                @if ($other)
                    <img src="{{ $other->avatar_url }}" class="w-8 h-8 rounded-full object-cover" />
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-[var(--text-primary)] dark:text-[var(--card-border)]">{{ $other->name }}</p>
                        <p class="text-[10px] text-[var(--text-secondary)]">@ {{ e($other->username_display) }}</p>
                    </div>
                @endif
            </div>

            <div wire:poll.10s class="flex-1 overflow-y-auto p-3 space-y-3">
                @forelse ($this->messages as $msg)
                    <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] {{ $msg->user_id === auth()->id() ? 'bg-[var(--accent)] text-white' : 'bg-[var(--surface-hover)] dark:bg-[var(--surface-hover)] text-[var(--text-primary)] dark:text-[var(--card-border)]' }} p-2.5 rounded-2xl {{ $msg->user_id === auth()->id() ? 'rounded-br-md' : 'rounded-bl-md' }}">
                            <p class="text-xs leading-relaxed whitespace-pre-line">{{ $msg->body }}</p>
                            <p class="text-[10px] {{ $msg->user_id === auth()->id() ? 'text-white/70' : 'text-[var(--text-secondary)]' }} mt-1 text-right">{{ $msg->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <p class="text-xs text-[var(--text-secondary)]">Belum ada pesan. Kirim pesan pertama!</p>
                    </div>
                @endforelse
            </div>

            <div class="p-3 border-t border-[var(--card-border)] dark:border-[var(--card-border)]">
                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model.live="body" 
                        placeholder="Tulis pesan..." 
                        class="flex-1 px-3 py-2 bg-[var(--surface-hover)] dark:bg-[var(--surface-hover)] text-xs text-[var(--text-primary)] dark:text-[var(--card-border)] rounded-full border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition"
                    />
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-full transition disabled:opacity-50 shrink-0 flex items-center gap-1"
                        {{ empty(trim($body)) ? 'disabled' : '' }}
                    >
                        <span class="material-symbols-outlined text-sm">send</span>
                        <span class="hidden sm:inline">Kirim</span>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 hidden sm:flex items-center justify-center">
                <div class="text-center space-y-2">
                    <span class="material-symbols-outlined text-5xl text-[#d9dade]">chat</span>
                    <p class="text-sm text-[var(--text-secondary)]">Pilih percakapan untuk mulai chat</p>
                </div>
            </div>
        @endif
    </div>
</div>

