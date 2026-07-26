<button 
    wire:click="toggleBookmark" 
    wire:loading.attr="disabled"
    class="py-1.5 rounded-xl flex items-center justify-center transition active:scale-90 disabled:opacity-50 {{ $isSaved ? 'text-[var(--accent)] bg-[var(--accent)]/10' : 'text-[var(--text-secondary)] hover:bg-[var(--surface-hover)]' }}"
    title="{{ $isSaved ? 'Hapus dari simpanan' : 'Simpan postingan' }}"
>
    <span aria-hidden="true" class="material-symbols-outlined text-lg {{ $isSaved ? 'filled' : '' }}">bookmark</span>
</button>
