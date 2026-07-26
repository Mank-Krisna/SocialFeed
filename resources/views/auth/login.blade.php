<x-app-layout>
    <div class="max-w-md mx-auto mt-10">
        <div class="card-elevation p-6 space-y-4">
            <h1 class="font-display font-bold text-xl text-center">Masuk ke SocialFeed</h1>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remember" class="rounded" />
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-[var(--accent)] hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full py-2.5 bg-[var(--accent)] text-white font-semibold rounded-xl hover:bg-[var(--accent-hover)] transition btn-press">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-[var(--text-secondary)]">
                Belum punya akun? <a href="{{ route('register') }}" class="text-[var(--accent)] hover:underline font-semibold">Daftar</a>
            </p>
        </div>
    </div>
</x-app-layout>
