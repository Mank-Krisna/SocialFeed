<x-guest-layout>
    <div class="space-y-5">
        <div class="text-center space-y-1">
            <h2 class="font-display font-bold text-2xl text-[var(--text-primary)]">Daftar Akun SocialFeed</h2>
            <p class="text-xs text-[var(--text-secondary)]">Bergabunglah bersama komunitas untuk mulai berbagi cerita</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    placeholder="Nama Lengkap"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
                @error('name')<p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
                @error('email')<p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="Minimal 8 karakter"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
                @error('password')<p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    placeholder="Ulangi password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
            </div>

            <button type="submit" class="w-full py-2.5 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-xl shadow-md transition btn-press mt-2">
                Daftar Akun Baru
            </button>
        </form>

        <p class="text-center text-xs text-[var(--text-secondary)] pt-2 border-t border-[var(--card-border)]">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-[var(--accent)] hover:underline font-bold">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>
