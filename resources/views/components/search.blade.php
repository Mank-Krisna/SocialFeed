@props([
    'placeholder' => 'Cari di SocialFeed...',
])

<form
    action="{{ route('search') }}"
    method="GET"
    x-data="{ q: '{{ request('q', '') }}' }"
    role="search"
    {{ $attributes }}
>
    <div class="relative flex-1">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--text-secondary)] text-xl pointer-events-none" aria-hidden="true">search</span>
        <input
            type="text"
            name="q"
            x-model="q"
            placeholder="{{ $placeholder }}"
            aria-label="Cari di SocialFeed"
            aria-autocomplete="both"
            inputmode="search"
            enterkeyhint="search"
            autocomplete="off"
            class="w-full pl-11 pr-9 py-2 bg-[var(--surface-hover)] focus:bg-[var(--card-bg)] text-sm text-[var(--text-primary)] rounded-full border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]"
        />
        <button
            type="button"
            x-show="q.length > 0"
            @click="q = ''; $el.parentElement.querySelector('input').focus()"
            class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1 text-[var(--text-secondary)] hover:text-[var(--text-primary)] hover:bg-[var(--surface-hover)] rounded-full transition"
            aria-label="Hapus pencarian"
        >
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>
    <button
        type="submit"
        class="min-w-[44px] min-h-[44px] p-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white rounded-full transition shrink-0 flex items-center justify-center"
        aria-label="Cari"
    >
        <span class="material-symbols-outlined text-lg">search</span>
    </button>
</form>
