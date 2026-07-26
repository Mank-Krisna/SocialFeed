<x-app-layout>
    <div class="max-w-md mx-auto mt-10">
        <div class="card-elevation p-6 space-y-4">
            <h1 class="font-display font-bold text-xl text-center">Konfirmasi Password</h1>
            <p class="text-sm text-center text-[var(--text-secondary)]">Masukkan password Anda untuk melanjutkan.</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password" required autofocus
                        class="w-full px-4 py-2.5 rounded-xl border border-[var(--card-border)] bg-[var(--surface-bg)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] outline-none transition" />
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full py-2.5 bg-[var(--accent)] text-white font-semibold rounded-xl hover:bg-[var(--accent-hover)] transition btn-press">
                    Konfirmasi
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
