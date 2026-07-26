<x-app-layout>
    <div class="space-y-6">
        <!-- Profile Banner Card -->
        <div class="card-elevation overflow-hidden">
            <!-- Cover Photo Banner -->
            <div class="h-32 sm:h-44 bg-gradient-to-r from-[var(--accent)] to-[var(--gold)] relative">
                @if ($user->cover_photo_url)
                    <img src="{{ $user->cover_photo_url }}" alt="Cover" class="w-full h-full object-cover" />
                @endif
            </div>

            <!-- Profile Info Header -->
            <div class="px-4 sm:px-4 pb-5 pt-0 relative">
                <div class="flex items-end justify-between gap-3 -mt-10 sm:-mt-14 mb-3">
                    <img 
                        src="{{ $user->avatar_url }}" 
                        alt="{{ $user->name }}" 
                        width="96" height="96"
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-white dark:border-[var(--text-primary)] shadow-md bg-white shrink-0"
                    />

                    @if (auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" wire:navigate class="px-3.5 py-2 bg-[var(--surface-hover)] dark:bg-[var(--surface-hover)] hover:bg-[var(--card-border)] dark:hover:bg-[var(--card-border)] text-[var(--text-primary)] dark:text-[var(--card-border)] text-xs font-bold rounded-xl transition inline-flex items-center gap-1.5 shrink-0 shadow-sm active:scale-95">
                            <span aria-hidden="true" class="material-symbols-outlined text-base">edit</span>
                            <span>Edit Profil</span>
                        </a>
                    @endif
                </div>

                <div class="space-y-1">
                    <h2 class="text-lg sm:text-xl font-bold text-[var(--text-primary)] dark:text-[var(--card-border)] tracking-tight">{{ $user->name }}</h2>
                    <p class="text-xs text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">{{ '@' . e($user->username_display) }}</p>
                    
                    @if ($user->bio)
                        <p class="text-xs sm:text-sm text-[var(--text-variant)] dark:text-[var(--text-secondary)] pt-2 whitespace-pre-line leading-relaxed">{{ $user->bio }}</p>
                    @endif
                </div>

                <!-- Stats summary -->
                <div class="flex items-center gap-4 pt-3 border-t border-[var(--surface-hover)] dark:border-[var(--card-border)] mt-4 text-xs">
                    <div>
                        <span class="font-bold text-[var(--text-primary)] dark:text-[var(--card-border)]">{{ $posts->count() }}</span>
                        <span class="text-[var(--text-secondary)] dark:text-[var(--text-secondary)]"> Postingan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Posts Stream -->
        <div class="space-y-4">
            <h3 class="font-bold text-sm text-[var(--text-primary)] dark:text-[var(--card-border)] px-1">Postingan oleh {{ $user->name }}</h3>

            @forelse ($posts as $post)
                <livewire:post-item :post="$post" :key="'user-post-'.$post->id" />
            @empty
                <div class="card-elevation p-4 text-center text-xs text-[var(--text-secondary)] dark:text-[var(--text-secondary)]">
                    Pengguna ini belum membuat postingan.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

