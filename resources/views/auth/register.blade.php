<x-app-layout>
    <div class="max-w-md mx-auto mt-10">
        <div class="card-elevation p-6 space-y-4">
            <h1 class="font-display font-bold text-xl text-center">Daftar SocialFeed</h1>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                </div>

                <button type="submit" class="w-full py-2.5 bg-[var(--accent)] text-white font-semibold rounded-xl hover:bg-[var(--accent-hover)] transition btn-press">
                    Daftar
                </button>
            </form>

            <p class="text-center text-sm text-[var(--text-secondary)]">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[var(--accent)] hover:underline font-semibold">Masuk</a>
            </p>
        </div>
    </div>
</x-app-layout>
