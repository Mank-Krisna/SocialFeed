<div class="card-elevation p-3 space-y-2">
    <form wire:submit.prevent="submit" class="space-y-2">
        <div class="flex items-start gap-3">
            <img 
                src="{{ auth()->user()->avatar_url }}" 
                alt="{{ auth()->user()->name }}" 
                width="40" height="40"
                class="w-10 h-10 rounded-full object-cover shrink-0 border border-[var(--card-border)]"
            />
            <div class="flex-1 space-y-2">
                <textarea 
                    wire:model.live="body" 
                    rows="3" 
                    name="body"
                    placeholder="Apa yang Anda pikirkan, {{ auth()->user()->name }}?…" 
                    class="w-full p-2.5 bg-[var(--surface-hover)] focus:bg-[var(--card-bg)] text-sm text-[var(--text-primary)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none resize-none transition input-glow"
                ></textarea>
                @error('body')
                    <span class="text-xs text-red-600 font-medium">{{ $message }}</span>
                @enderror

                <!-- Media Thumbnails Preview Grid (Photos & Videos) -->
                @if (!empty($mediaFiles))
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach ($mediaFiles as $index => $file)
                            @php
                                $isImage = false;
                                try {
                                    $isImage = method_exists($file, 'isPreviewable') && $file->isPreviewable();
                                } catch (\Throwable $e) {
                                    $isImage = false;
                                }
                            @endphp
                            <div class="relative rounded-lg overflow-hidden border border-[var(--card-border)] aspect-video group bg-slate-900 flex items-center justify-center">
                                @if ($isImage)
                                    <img src="{{ $file->temporaryUrl() }}" alt="Preview" width="400" height="225" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-white p-2 text-center bg-slate-800">
                                        <span aria-hidden="true" class="material-symbols-outlined text-3xl text-purple-400">movie</span>
                                        <span class="text-[10px] font-semibold truncate max-w-full px-1">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                @endif
                                <button 
                                    type="button" 
                                    wire:click="removeMedia({{ $index }})" 
                                    class="absolute top-1 right-1 bg-black/70 hover:bg-red-600 text-white rounded-full p-1 transition shadow-sm z-10"
                                    title="Hapus"
                                >
                                    <span class="material-symbols-outlined text-xs">close</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('mediaFiles.*')
                    <span class="text-xs text-red-600 font-medium block pt-1">{{ $message }}</span>
                @enderror
                @error('mediaFiles')
                    <span class="text-xs text-red-600 font-medium block pt-1">{{ $message }}</span>
                @enderror

                <!-- Upload Progress Indicator -->
                <div wire:loading wire:target="mediaFiles" class="text-xs text-[var(--accent)] font-semibold flex items-center gap-1.5">
                    <span aria-hidden="true" class="material-symbols-outlined text-sm animate-spin">progress_activity</span>
                    <span>Mengunggah berkas media...</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 pt-1.5 border-t border-[var(--surface-hover)]">
            <div class="flex items-center gap-1.5">
                <label class="inline-flex items-center gap-1 px-2.5 py-1 bg-[var(--surface-hover)] hover:bg-[var(--card-border)] text-[var(--text-variant)] text-xs font-semibold rounded-lg cursor-pointer transition">
                    <span aria-hidden="true" class="material-symbols-outlined text-base text-emerald-600">image</span>
                    <span>Foto</span>
                    <input type="file" wire:model="mediaFiles" multiple accept="image/*" class="hidden" />
                </label>

                <label class="inline-flex items-center gap-1 px-2.5 py-1 bg-[var(--surface-hover)] hover:bg-[var(--card-border)] text-[var(--text-variant)] text-xs font-semibold rounded-lg cursor-pointer transition">
                    <span aria-hidden="true" class="material-symbols-outlined text-base text-purple-600">movie</span>
                    <span>Video</span>
                    <input type="file" wire:model="mediaFiles" multiple accept="video/*" class="hidden" />
                </label>

                <div class="text-xs font-medium text-[var(--text-secondary)] ms-1">
                    <span class="{{ mb_strlen($body) > 480 ? 'text-red-500 font-bold' : '' }}">
                        {{ mb_strlen($body) }}/500
                    </span>
                </div>
            </div>

            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="px-4 py-1.5 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-sm font-bold rounded-lg transition btn-press disabled:opacity-50 flex items-center gap-1 shadow-sm"
            >
                <span wire:loading.remove wire:target="submit" aria-hidden="true" class="material-symbols-outlined text-base">send</span>
                <span wire:loading wire:target="submit" aria-hidden="true" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span>Posting</span>
            </button>
        </div>
    </form>
</div>
