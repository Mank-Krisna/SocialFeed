<div class="space-y-3">
    <!-- Suggested Connections Card -->
    <div class="card-elevation p-3 space-y-2">
        <h4 class="font-bold text-sm text-[var(--text-primary)] flex items-center justify-between">
            <span>Saran Teman</span>
            <span class="material-symbols-outlined text-base text-[var(--text-secondary)]" aria-hidden="true">person_add</span>
        </h4>
        <div class="space-y-2">
            @forelse ($suggestedUsers as $suggested)
                <div class="flex items-center justify-between gap-1.5">
                    <a href="{{ route('profile.show', $suggested['username'] ?? $suggested['id']) }}" wire:navigate class="flex items-center gap-1.5 min-w-0 flex-1">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[var(--accent)] to-[var(--gold)] text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                            {{ $suggested['initial'] }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-semibold text-[var(--text-primary)] truncate">{{ $suggested['name'] }}</p>
                            <p class="text-[10px] text-[var(--text-secondary)] truncate">@<span>{{ $suggested['username_display'] }}</span></p>
                        </div>
                    </a>
                    <button wire:click="$dispatch('add-friend', { userId: {{ $suggested['id'] }} })" class="px-2 py-0.5 text-[10px] font-semibold text-[var(--accent)] bg-[var(--accent)]/10 hover:bg-[var(--accent)] hover:text-white rounded-lg transition shrink-0">
                        +Tambah
                    </button>
                </div>
            @empty
                <p class="text-xs text-[var(--text-secondary)] text-center py-2">Belum ada saran teman.</p>
            @endforelse
        </div>
    </div>

    @if ($trendingHashtags->count() > 0)
    <div class="card-elevation p-3 space-y-2">
        <h4 class="font-bold text-sm text-[var(--text-primary)] flex items-center justify-between">
            <span>Trending Saat Ini</span>
            <span aria-hidden="true" class="material-symbols-outlined text-base text-[var(--text-secondary)]">trending_up</span>
        </h4>
        <div class="space-y-1.5">
            @foreach ($trendingHashtags as $idx => $tag)
                @php
                    $colors = ['bg-[var(--accent)]', 'bg-[var(--gold)]', 'bg-[var(--accent-hover)]'];
                    $rankColor = $colors[$idx] ?? 'bg-[var(--surface-hover)]';
                    $textColor = $idx < 3 ? 'text-white' : 'text-[var(--text-variant)]';
                @endphp
                <a href="{{ route('search', ['q' => '%23'.$tag->name]) }}" wire:navigate class="flex items-center gap-2 {{ $idx > 0 ? 'pt-1.5 border-t border-[var(--surface-hover)]' : '' }}">
                    <span class="w-5 h-5 rounded flex items-center justify-center text-[9px] font-bold shrink-0 {{ $rankColor }} {{ $textColor }}">{{ $idx + 1 }}</span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-[var(--text-primary)] hover:text-[var(--accent)] transition truncate">#{{ $tag->name }}</p>
                        <p class="text-[10px] text-[var(--text-secondary)]">{{ $tag->posts_count }} postingan</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

