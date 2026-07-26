<x-guest-layout>
    <div class="space-y-5">
        <div class="text-center space-y-1">
            <h2 class="font-display font-bold text-2xl text-[var(--text-primary)]">Masuk ke SocialFeed</h2>
            <p class="text-xs text-[var(--text-secondary)]">Masukkan email dan password kamu untuk melanjutkan</p>
        </div>

        @if (session('status'))
            <div class="p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 text-xs font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@email.com"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
                @error('email')<p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-[var(--text-primary)] mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] text-xs text-[var(--text-primary)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition placeholder:text-[var(--text-secondary)]/50" />
                @error('password')<p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-[var(--text-secondary)]">
                    <input type="checkbox" name="remember" class="rounded accent-[var(--accent)]" />
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[var(--accent)] hover:underline font-semibold">Lupa password?</a>
                @endif
            </div>

            <button type="submit" class="w-full py-2.5 bg-[var(--accent)] hover:bg-[var(--accent-hover)] text-white text-xs font-bold rounded-xl shadow-md transition btn-press">
                Masuk
            </button>
        </form>

        <p class="text-center text-xs text-[var(--text-secondary)] pt-2 border-t border-[var(--card-border)]">
            Belum punya akun? <a href="{{ route('register') }}" class="text-[var(--accent)] hover:underline font-bold">Daftar Akun Baru</a>
        </p>
    </div>
</x-guest-layout>
