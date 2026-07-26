<div>
    @if($show)
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
        x-data
        x-init="document.body.style.overflow = 'hidden'"
        x-destroy="document.body.style.overflow = ''"
    >
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="close"></div>

        {{-- Modal Panel --}}
        <div class="relative z-10 w-full max-w-sm mx-4 mb-4 sm:mb-0 bg-white rounded-2xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-[var(--surface-hover)]">
                <h2 class="font-bold text-base text-[var(--text-primary)]">Laporkan Konten</h2>
                <button wire:click="close" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-[var(--surface-hover)] transition">
                    <span class="material-symbols-outlined text-lg text-[var(--text-secondary)]">close</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-5 py-4">
                @if($successMessage)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 text-green-700 text-sm font-medium">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        {{ $successMessage }}
                    </div>
                    <button wire:click="close" class="mt-4 w-full py-2.5 rounded-xl bg-[var(--accent)] text-white text-sm font-bold transition hover:bg-[var(--accent-hover)]">
                        Tutup
                    </button>
                @else
                    <p class="text-sm text-[var(--text-secondary)] mb-4">Pilih alasan kenapa kamu melaporkan konten ini:</p>

                    @error('reason')
                        <div class="mb-3 p-2.5 rounded-xl bg-red-50 text-red-600 text-xs font-medium">{{ $message }}</div>
                    @enderror

                    <div class="space-y-2">
                        @foreach($reasons as $key => $label)
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition
                                {{ $reason === $key ? 'border-[var(--accent)] bg-blue-50' : 'border-[var(--card-border)] hover:border-[var(--accent)]/40' }}">
                                <input
                                    type="radio"
                                    wire:model="reason"
                                    value="{{ $key }}"
                                    class="accent-[var(--accent)]"
                                />
                                <span class="text-sm font-medium text-[var(--text-primary)]">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <button
                        wire:click="submit"
                        wire:loading.attr="disabled"
                        class="mt-4 w-full py-2.5 rounded-xl bg-red-500 text-white text-sm font-bold transition hover:bg-red-600 disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="submit">Kirim Laporan</span>
                        <span wire:loading wire:target="submit">Mengirim...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

