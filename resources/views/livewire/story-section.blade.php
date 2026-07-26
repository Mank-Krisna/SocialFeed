<div wire:poll.30s="loadStories" class="card-elevation p-3 overflow-hidden">
    {{-- Upload form --}}
    @if ($showUploadForm)
        <div class="mb-3 p-3 bg-[#f8f9fa] dark:bg-[#25282e] rounded-xl space-y-3">
            <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress" class="space-y-3">
                <label class="block text-sm font-semibold text-[#414754] dark:text-[#b0b4be]">Pilih foto atau video</label>
                <input type="file" wire:model="mediaFile" accept="image/*,video/*" class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#0058bc] file:text-white hover:file:bg-[#004493]">
                <div x-show="uploading" class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-[#0058bc] h-2 rounded-full" x-bind:style="`width: ${progress}%`"></div>
                </div>
                <textarea wire:model="caption" placeholder="Tulis caption..." class="w-full rounded-lg border border-[#ced0d4] dark:border-[#3e4148] bg-white dark:bg-[#1a1c1f] px-3 py-2 text-sm text-[#1a1c1f] dark:text-white resize-none" rows="2"></textarea>
                <div class="flex gap-2">
                    <button wire:click="uploadStory" class="px-4 py-2 bg-[#0058bc] text-white rounded-lg text-sm font-semibold hover:bg-[#004493] transition">Unggah</button>
                    <button wire:click="toggleUploadForm" class="px-4 py-2 bg-gray-200 dark:bg-[#33363d] text-[#414754] dark:text-[#b0b4be] rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-[#3e4148] transition">Batal</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Stories row --}}
    <div class="flex items-center gap-4 overflow-x-auto scrollbar-hide pb-1">
        {{-- Create story button --}}
        <button wire:click="toggleUploadForm" class="flex flex-col items-center gap-1 shrink-0">
            <div class="w-16 h-16 rounded-full border-2 border-dashed border-[#0058bc] flex items-center justify-center bg-[#f0f4ff] dark:bg-[#1a2332] hover:bg-[#e0ebff] dark:hover:bg-[#1f2a3d] transition">
                <span class="material-symbols-outlined text-2xl text-[#0058bc]">add</span>
            </div>
            <span class="text-[10px] font-semibold text-[#414754] dark:text-[#b0b4be] truncate w-16 text-center">Buat Cerita</span>
        </button>

        {{-- User's own story --}}
        @php
            $myStories = $stories->first(fn ($g) => $g['user']->id === auth()->id());
        @endphp
        @if ($myStories)
            <button wire:click="$dispatch('open-story-viewer', { userId: {{ auth()->id() }} })" class="flex flex-col items-center gap-1 shrink-0">
                <div class="w-16 h-16 rounded-full ring-2 ring-[#0058bc] ring-offset-2 dark:ring-offset-[#1a1c1f] overflow-hidden">
                    <img src="{{ $myStories['user']->avatar_url }}" alt="" class="w-full h-full object-cover">
                </div>
                <span class="text-[10px] font-semibold text-[#414754] dark:text-[#b0b4be] truncate w-16 text-center">Kamu</span>
            </button>
        @endif

        {{-- Friends' stories --}}
        @foreach ($stories as $group)
            @if ($group['user']->id === auth()->id()) @continue @endif
            <button wire:click="$dispatch('open-story-viewer', { userId: {{ $group['user']->id }} })" class="flex flex-col items-center gap-1 shrink-0">
                <div class="w-16 h-16 rounded-full {{ $group['hasUnviewed'] ? 'ring-2 ring-[#0058bc] ring-offset-2 dark:ring-offset-[#1a1c1f]' : 'ring-2 ring-[#ced0d4] dark:ring-[#3e4148] ring-offset-2 dark:ring-offset-[#1a1c1f]' }} overflow-hidden">
                    <img src="{{ $group['user']->avatar_url }}" alt="" class="w-full h-full object-cover">
                </div>
                <span class="text-[10px] font-semibold text-[#414754] dark:text-[#b0b4be] truncate w-16 text-center">{{ $group['user']->name }}</span>
            </button>
        @endforeach
    </div>
</div>
