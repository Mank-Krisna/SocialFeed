<div class="space-y-2 pt-2">
    <p class="text-sm font-bold text-[#1a1c1f] dark:text-[#e2e2e6]">{{ $poll->question }}</p>

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
                    {{ $selectedOptionId === $option->id ? 'border-[#0058bc] bg-[#0058bc]/5' : 'border-[#e2e2e6] dark:border-[#2d2f34] hover:bg-[#f3f3f7] dark:hover:bg-[#25282e]' }}
                    {{ $hasVoted ? 'cursor-default' : 'cursor-pointer' }}"
            >
                <div 
                    class="absolute inset-0 transition-all duration-500 rounded-lg {{ $selectedOptionId === $option->id ? 'bg-[#0058bc]/10' : 'bg-[#f3f3f7] dark:bg-[#25282e]' }}"
                    style="width: {{ $hasVoted ? $pct : 0 }}%"
                ></div>
                <div class="relative flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold text-[#414754] dark:text-[#b0b4be]">{{ $option->label }}</span>
                    @if ($hasVoted)
                        <span class="text-xs font-bold text-[#727785] dark:text-[#9ca3af]">{{ $pct }}%</span>
                    @endif
                </div>
            </button>
        @endforeach
    </div>

    <p class="text-[11px] text-[#727785] dark:text-[#9ca3af]">
        {{ $totalVotes }} suara
        @if ($poll->ends_at)
            · {{ $poll->ends_at->isPast() ? 'Berakhir' : 'Berakhir' }} {{ $poll->ends_at->diffForHumans() }}
        @endif
    </p>
</div>
