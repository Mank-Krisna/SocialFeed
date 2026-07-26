<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SocialFeed — Ruang Berbagi Indonesia</title>
        <meta name="description" content="SocialFeed adalah platform media sosial Indonesia untuk berbagi postingan, foto, cerita, dan berinteraksi dengan komunitas. Bergabung sekarang.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/') }}">

        <meta property="og:type" content="website">
        <meta property="og:title" content="SocialFeed — Ruang Berbagi Indonesia">
        <meta property="og:description" content="Platform media sosial Indonesia untuk berbagi postingan, foto, cerita, dan berinteraksi dengan komunitas.">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:site_name" content="SocialFeed">
        <meta property="og:locale" content="id_ID">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="SocialFeed — Ruang Berbagi Indonesia">
        <meta name="twitter:description" content="Platform media sosial Indonesia untuk berbagi postingan, foto, cerita, dan berinteraksi dengan komunitas.">
        <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#1e1b4b">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased min-h-screen bg-[var(--bg-page)]">

        {{-- Navigation --}}
        <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl bg-[var(--nav-bg)]/80 border-b border-[var(--card-border)]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Logo --}}
                    <a href="/" class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[var(--accent)] flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xl filled" style="font-variation-settings: 'FILL' 1">forum</span>
                        </div>
                        <span class="font-display font-extrabold text-xl text-[var(--text-primary)]">Social<span class="text-[var(--accent)]">Feed</span></span>
                    </a>

                    {{-- Auth Actions --}}
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/feed') }}" class="px-5 py-2 bg-[var(--accent)] text-white text-sm font-semibold rounded-full hover:bg-[var(--accent-hover)] transition shadow-sm btn-press">
                                Buka Feed
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-[var(--text-primary)] hover:text-[var(--accent)] transition btn-press">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-[var(--accent)] text-white text-sm font-semibold rounded-full hover:bg-[var(--accent-hover)] transition shadow-sm btn-press">
                                Daftar Gratis
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <section class="relative pt-32 pb-20 sm:pt-40 sm:pb-28 overflow-hidden">
            {{-- Background accents --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-[var(--accent)]/5 blur-3xl"></div>
                <div class="absolute top-20 -left-32 w-72 h-72 rounded-full bg-[var(--gold)]/8 blur-3xl"></div>
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="max-w-2xl mx-auto text-center">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--accent-container)] text-[var(--accent)] text-xs font-semibold mb-6 border border-[var(--accent)]/10">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--accent)] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[var(--accent)]"></span>
                        </span>
                        Platform Media Sosial Indonesia
                    </div>

                    {{-- Headline --}}
                    <h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl text-[var(--text-primary)] leading-tight tracking-tight mb-6">
                        Ruang Berbagi<br>
                        <span class="text-[var(--accent)]">untuk Semua</span>
                    </h1>

                    {{-- Subhead --}}
                    <p class="text-lg sm:text-xl text-[var(--text-secondary)] leading-relaxed max-w-lg mx-auto mb-8">
                        Berbagi cerita, terhubung dengan komunitas, dan menemukan hal baru — semua di satu tempat yang nyaman.
                    </p>

                    {{-- CTA --}}
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @auth
                            <a href="{{ url('/feed') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[var(--accent)] text-white font-bold rounded-full hover:bg-[var(--accent-hover)] transition shadow-md btn-press text-base">
                                Buka Feed
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[var(--accent)] text-white font-bold rounded-full hover:bg-[var(--accent-hover)] transition shadow-md btn-press text-base">
                                Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[var(--surface-hover)] text-[var(--text-primary)] font-bold rounded-full hover:bg-[var(--card-border)] transition btn-press text-base">
                                Sudah Punya Akun?
                            </a>
                        @endauth
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center justify-center gap-8 mt-12 text-sm">
                        <div class="text-center">
                            <div class="font-display font-extrabold text-2xl text-[var(--accent)]">12K+</div>
                            <div class="text-[var(--text-secondary)] mt-0.5">Pengguna Aktif</div>
                        </div>
                        <div class="w-px h-8 bg-[var(--card-border)]"></div>
                        <div class="text-center">
                            <div class="font-display font-extrabold text-2xl text-[var(--accent)]">50K+</div>
                            <div class="text-[var(--text-secondary)] mt-0.5">Postingan</div>
                        </div>
                        <div class="w-px h-8 bg-[var(--card-border)]"></div>
                        <div class="text-center">
                            <div class="font-display font-extrabold text-2xl text-[var(--accent)]">100+</div>
                            <div class="text-[var(--text-secondary)] mt-0.5">Komunitas</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Ornamental Divider --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="ornament-divider">
                <span class="ornament-dot"></span>
                <span class="ornament-dot"></span>
                <span class="ornament-dot"></span>
            </div>
        </div>

        {{-- Features Section --}}
        <section class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--text-primary)] mb-3">
                        Kenapa SocialFeed?
                    </h2>
                    <p class="text-[var(--text-secondary)] max-w-md mx-auto">
                        Dirancang untuk pengalaman yang nyaman, aman, dan bermakna.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Feature 1 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">dynamic_feed</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Feed yang Relevan</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Lihat postingan dari teman dan komunitas yang kamu ikuti, diurutkan secara cerdas.</p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">groups</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Komunitas</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Bergabung dengan grup sesuai minatmu. Diskusi, berbagi, dan tumbuh bersama.</p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">chat</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Pesan Instan</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Obrolan privat dan grup dengan antarmuka yang cepat dan intuitif.</p>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">bookmark</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Simpan & Temukan</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Tandai postingan favoritmu dan temukan konten baru lewat pencarian hashtag.</p>
                    </div>

                    {{-- Feature 5 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">shield</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Aman & Terkendali</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Laporkan konten yang tidak pantas. Moderasi aktif untuk menjaga kenyamanan.</p>
                    </div>

                    {{-- Feature 6 --}}
                    <div class="card-elevation p-6 feature-card">
                        <div class="w-11 h-11 rounded-xl bg-[var(--accent-container)] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-[var(--accent)] text-2xl" style="font-variation-settings: 'FILL' 1">notifications</span>
                        </div>
                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2">Notifikasi Cerdas</h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed">Dapatkan kabar saat ada yang membalas, menyukai, atau mengundangmu ke grup.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Community Section --}}
        <section class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="card-elevation overflow-hidden">
                    <div class="pulse-bar h-1"></div>
                    <div class="p-8 sm:p-12 text-center">
                        <div class="inline-flex items-center gap-2 text-sm text-[var(--accent)] font-semibold mb-4">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--accent)] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[var(--accent)]"></span>
                            </span>
                            SocialFeed — Ruang Berbagi Indonesia
                        </div>
                        <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-[var(--text-primary)] mb-3">
                            Bergabung dengan Komunitas
                        </h2>
                        <p class="text-[var(--text-secondary)] max-w-md mx-auto mb-6">
                            Temukan orang-orang yang punya minat sama. Mulai percakapan yang bermakna.
                        </p>
                        @guest
                            <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-[var(--accent)] text-white font-bold rounded-full hover:bg-[var(--accent-hover)] transition shadow-md btn-press">
                                Daftar Gratis
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="border-t border-[var(--card-border)] py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-[var(--text-secondary)]">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[var(--accent)] flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-base filled" style="font-variation-settings: 'FILL' 1">forum</span>
                        </div>
                        <span class="font-display font-bold text-[var(--text-primary)]">SocialFeed</span>
                    </div>
                    <div>&copy; {{ date('Y') }} SocialFeed. Dibuat di Indonesia.</div>
                </div>
            </div>
        </footer>

    </body>
</html>
