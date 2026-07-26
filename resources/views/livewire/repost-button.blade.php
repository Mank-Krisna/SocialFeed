<button 
    wire:click="repost" 
    wire:loading.attr="disabled"
    class="py-1.5 rounded-xl flex items-center justify-center gap-1 text-sm font-semibold transition active:scale-90 disabled:opacity-50 text-[var(--text-secondary)] hover:bg-[var(--surface-hover)]"
    title="Bagikan Ulang"
>
    <span aria-hidden="true" class="material-symbols-outlined text-xl">repeat</span>
    <span wire:loading.remove wire:target="repost">{{ $repostsCount > 0 ? $repostsCount : 'Bagikan' }}</span>
    <span wire:loading wire:target="repost" class="text-xs">...</span>
</button>
