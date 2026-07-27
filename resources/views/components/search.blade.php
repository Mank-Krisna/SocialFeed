@props([
    'placeholder' => 'Cari di SocialFeed...',
    'showButton' => false,
])

<form
    action="{{ route('search') }}"
    method="GET"
    x-data="{ q: '{{ request('q', '') }}' }"
    role="search"
    {{ $attributes->merge(['class' => 'relative w-full flex items-center']) }}
>
    <div class="relative w-full">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-secondary)] text-lg pointer-events-none" aria-hidden="true">search</span>
        <input
            type="text"
            name="q"
            x-model="q"
            placeholder="{{ $placeholder }}"
            aria-label="Cari di SocialFeed"
            autocomplete="off"
            class="w-full pl-9 pr-8 py-2 bg-[var(--surface-hover)] focus:bg-[var(--card-bg)] text-xs font-medium text-[var(--text-primary)] rounded-full border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]"
        />
        <button
            type="button"
            x-show="q.length > 0"
            @click="q = ''; $el.parentElement.querySelector('input').focus()"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-0.5 text-[var(--text-secondary)] hover:text-[var(--text-primary)] rounded-full transition"
            aria-label="Hapus pencarian"
        >
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>
    @if ($showButton)
        <button
            type="submit"
            class="px-3.5 py-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-full transition shrink-0 flex items-center justify-center ml-2"
            aria-label="Cari"
        >
            <span class="material-symbols-outlined text-base">search</span>
        </button>
    @endif
</form>
