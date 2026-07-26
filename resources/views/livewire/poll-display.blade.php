<div class="space-y-2 pt-2">
    <p class="text-sm font-bold text-[var(--text-primary)] dark:text-[var(--card-border)]">{{ $poll->question }}</p>

    <div class="space-y-1.5">
        @foreach ($options as $option)
            @php 
                $pct = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100) : 0;
            @endphp
            <button 
                wire:click="vote({{ $option->id }})" 
                wire:loading.attr="disabled"
                @if ($hasVoted || $selectedOptionId) disabled @endif
                class="relative w-full text-start p-2.5 rounded-lg border transition overflow-hidden
                    {{ $selectedOptionId === $option->id ? 'border-[var(--accent)] bg-[var(--accent)]/5' : 'border-[var(--card-border)] dark:border-[var(--card-border)] hover:bg-[var(--surface-hover)] dark:hover:bg-[var(--surface-hover)]' }}
                    {{ $hasVoted ? 'cursor-default' : 'cursor-pointer' }}"
            >
                <div 
                    class="absolute inset-0 transition-all duration-500 rounded-lg {{ $selectedOptionId === $option->id ? 'bg-[var(--accent)]/10' : 'bg-[var(--surface-hover)] dark:bg-[var(--surface-hover)]' }}"
                    style="width: {{ $hasVoted ? $pct : 0 }}%"
                ></div>
                <div class="relative flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold text-[var(--text-variant)] dark:text-[var(--text-secondary)]">{{ $option->label }}</span>
                    @if ($hasVoted)
                        <span class="text-xs font-bold text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">{{ $pct }}%</span>
                    @endif
                </div>
            </button>
        @endforeach
    </div>

    <p class="text-[11px] text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">
        {{ $totalVotes }} suara
        @if ($poll->ends_at)
            · {{ $poll->ends_at->isPast() ? 'Berakhir' : 'Berakhir' }} {{ $poll->ends_at->diffForHumans() }}
        @endif
    </p>
</div>

