<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-[#1a1c1f]">
            Informasi Profil
        </h2>
        <p class="mt-1 text-xs text-[#727785]">
            Perbarui data profil, foto profil, foto sampul, dan bio akun Anda.
        </p>
    </header>

    <form wire:submit.prevent="updateProfileInformation" class="space-y-4">
        <!-- Cover Photo Upload — top banner -->
        <div>
            <x-input-label for="cover_photo" value="Foto Sampul (Cover)" />
            <div class="mt-1 mb-3">
                @if ($cover_photo && $cover_photo->isPreviewable())
                    <div class="w-full h-40 md:h-48 rounded-xl overflow-hidden bg-[#f3f3f7]">
                        <img src="{{ $cover_photo->temporaryUrl() }}" alt="Cover Preview" width="800" height="300" class="w-full h-full object-cover">
                    </div>
                @elseif (auth()->user()->cover_photo_url)
                    <div class="w-full h-40 md:h-48 rounded-xl overflow-hidden bg-[#f3f3f7]">
                        <img src="{{ auth()->user()->cover_photo_url }}" alt="Current Cover" width="800" height="300" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-40 md:h-48 rounded-xl bg-[#f3f3f7] flex items-center justify-center text-[#727785]">
                        <span class="text-xs">Belum ada foto sampul</span>
                    </div>
                @endif
            </div>
            <input type="file" wire:model="cover_photo" id="cover_photo" accept="image/*" class="text-xs text-[#727785] file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#0058bc]/10 file:text-[#0058bc] hover:file:bg-[#0058bc]/20" />
            <x-input-error class="mt-1" :messages="$errors->get('cover_photo')" />
        </div>

        <!-- Avatar Preview & Upload -->
        <div class="flex items-center gap-4">
            <img 
                src="{{ $avatar ? $avatar->temporaryUrl() : auth()->user()->avatar_url }}" 
                alt="Avatar" 
                width="64" height="64"
                class="w-16 h-16 rounded-full object-cover border-2 border-[#0058bc]"
            />
            <div class="space-y-1">
                <x-input-label for="avatar" value="Foto Profil" />
                <input type="file" wire:model="avatar" id="avatar" accept="image/*" class="text-xs text-[#727785] file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#0058bc]/10 file:text-[#0058bc] hover:file:bg-[#0058bc]/20" />
                <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input wire:model="name" id="name" type="text" class="mt-1 block w-full text-sm" required autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Username -->
        <div>
            <x-input-label for="username" value="Username (@handle)" />
            <x-text-input wire:model="username" id="username" type="text" class="mt-1 block w-full text-sm" placeholder="username_anda" />
            <x-input-error class="mt-1" :messages="$errors->get('username')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" value="Bio Singkat" />
            <textarea wire:model="bio" id="bio" rows="3" placeholder="Tuliskan sedikit tentang diri Anda..." class="mt-1 block w-full text-sm rounded-lg border-[#e2e2e6] focus:border-[#0058bc] focus:ring-[#0058bc]"></textarea>
            <x-input-error class="mt-1" :messages="$errors->get('bio')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full text-sm" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2 bg-[#0058bc] hover:bg-[#004493] text-white text-xs font-bold rounded-lg transition shadow-sm">
                Simpan Perubahan
            </button>

            <x-action-message class="me-3 text-xs text-emerald-600 font-semibold" on="profile-updated">
                Berhasil disimpan!
            </x-action-message>
        </div>
    </form>
</section>
