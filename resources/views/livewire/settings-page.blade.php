<div class="max-w-2xl mx-auto space-y-4">
    <div class="card-elevation p-4">
        <h1 class="font-display font-bold text-lg text-[var(--text-primary)] mb-4">Pengaturan</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 text-sm rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none text-sm input-glow" required>
                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none text-sm input-glow" required>
                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Bio</label>
                <textarea name="bio" rows="3" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none text-sm resize-none input-glow" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                @error('bio') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $user->location) }}" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none text-sm input-glow">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $user->website) }}" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none text-sm input-glow">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Visibilitas Profil</label>
                    <select name="profile_visibility" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] outline-none text-sm">
                        <option value="public" {{ old('profile_visibility', $user->profile_visibility ?? 'public') === 'public' ? 'selected' : '' }}>Publik</option>
                        <option value="friends" {{ old('profile_visibility', $user->profile_visibility ?? 'public') === 'friends' ? 'selected' : '' }}>Teman Saja</option>
                        <option value="private" {{ old('profile_visibility', $user->profile_visibility ?? 'public') === 'private' ? 'selected' : '' }}>Privat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Visibilitas Postingan</label>
                    <select name="post_visibility" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] outline-none text-sm">
                        <option value="public" {{ old('post_visibility', $user->post_visibility ?? 'public') === 'public' ? 'selected' : '' }}>Publik</option>
                        <option value="friends" {{ old('post_visibility', $user->post_visibility ?? 'public') === 'friends' ? 'selected' : '' }}>Teman Saja</option>
                        <option value="private" {{ old('post_visibility', $user->post_visibility ?? 'public') === 'private' ? 'selected' : '' }}>Privat</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-sm font-bold rounded-lg transition btn-press">Simpan Pengaturan</button>
        </form>
    </div>

    <div class="card-elevation p-4">
        <h2 class="font-display font-bold text-base text-[var(--text-primary)] mb-3">Ubah Password</h2>
        <form action="{{ route('settings.password') }}" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Password Lama</label>
                <input type="password" name="current_password" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] outline-none text-sm input-glow" required>
                @error('current_password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Password Baru</label>
                <input type="password" name="password" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] outline-none text-sm input-glow" required>
                @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-[var(--text-primary)] mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-[var(--accent)] outline-none text-sm input-glow" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-sm font-bold rounded-lg transition btn-press">Ubah Password</button>
        </form>
    </div>

    <div class="card-elevation p-4 border border-red-200 dark:border-red-500/20">
        <h2 class="font-display font-bold text-base text-red-600 mb-2">Hapus Akun</h2>
        <p class="text-sm text-[var(--text-secondary)] mb-3">Menghapus akun akan menghapus semua data secara permanen.</p>
        <form action="{{ route('settings.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-2">
                <input type="password" name="password" placeholder="Masukkan password untuk konfirmasi" class="flex-1 p-2.5 bg-[var(--surface-hover)] rounded-xl border border-transparent focus:border-red-500 outline-none text-sm" required>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition btn-press">Hapus Akun</button>
            </div>
        </form>
    </div>
</div>
