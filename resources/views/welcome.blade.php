<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SocialFeed - Connect with the World</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f9f9fd] text-[#1a1c1f] font-sans antialiased min-h-screen flex flex-col justify-between">
        <!-- Top Navbar Header -->
        <header class="bg-white border-b border-[#e2e2e6] sticky top-0 z-50">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-4 lg:px-8 h-16 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2.5 font-bold text-xl text-[#0058bc]">
                    <img src="{{ asset('images/logo.png') }}" alt="SocialFeed Logo" width="36" height="36" class="w-9 h-9 rounded-xl object-cover shadow-sm border border-[#e2e2e6]">
                    <span class="font-display tracking-tight">SocialFeed</span>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('feed') }}" class="px-5 py-2 bg-[#0058bc] hover:bg-[#004493] text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Buka Feed Utama &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold text-[#414754] hover:bg-[#f3f3f7] rounded-lg transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-[#0058bc] hover:bg-[#004493] text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Landing Content -->
        <main class="flex-1 max-w-[1100px] w-full mx-auto px-4 py-16 flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full">
                <!-- Left: Hero Text -->
                <div class="space-y-4 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#0058bc]/10 text-[#0058bc] rounded-full text-xs font-bold">
                        <span aria-hidden="true" class="material-symbols-outlined text-sm">sparkles</span>
                        <span>Social Media V1 MVP Realtime</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-black text-[#1a1c1f] leading-tight tracking-tight font-display">
                        Terhubung, Berbagi Status &amp; Berdiskusi Dalam Satu Tempat.
                    </h1>

                    <p class="text-base sm:text-lg text-[#414754] leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Nikmati pengalaman berjejaring sosial yang bersih, cepat, dan modern tanpa kebisingan iklan.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        @auth
                            <a href="{{ route('feed') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#0058bc] hover:bg-[#004493] text-white font-bold text-sm rounded-xl transition shadow-md text-center">
                                Masuk ke Feed Utama
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#0058bc] hover:bg-[#004493] text-white font-bold text-sm rounded-xl transition shadow-md text-center">
                                Mulai Sekarang (Gratis)
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-4 py-3.5 bg-[#f3f3f7] hover:bg-[#e2e2e6] text-[#1a1c1f] font-bold text-sm rounded-xl transition text-center">
                                Sudah Punya Akun
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right: Visual Cards Feature Showcase -->
                <div class="space-y-4">
                    <div class="card-elevation p-5 space-y-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" alt="SocialFeed Logo" width="40" height="40" class="w-10 h-10 rounded-xl object-cover shrink-0 shadow-sm border border-[#e2e2e6]">
                            <div>
                                <h4 class="font-display font-bold text-sm text-[#1a1c1f] tracking-tight">Fitur Utama SocialFeed V1</h4>
                                <p class="text-xs text-[#727785]">Laravel 11 + Livewire 3 Stack</p>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 text-xs">
                            <div class="flex items-center gap-2 text-[#414754]">
                                <span aria-hidden="true" class="material-symbols-outlined text-[#0058bc] text-base">check_circle</span>
                                <span>Status Posting Teks dengan Hitung Karakter</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#414754]">
                                <span aria-hidden="true" class="material-symbols-outlined text-[#0058bc] text-base">check_circle</span>
                                <span>Feed Global Realtime Otomatis Update</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#414754]">
                                <span aria-hidden="true" class="material-symbols-outlined text-[#0058bc] text-base">check_circle</span>
                                <span>Fitur Like/Unlike Instan tanpa Reload</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#414754]">
                                <span aria-hidden="true" class="material-symbols-outlined text-[#0058bc] text-base">check_circle</span>
                                <span>Komentar Bersarang (Nested Replies Thread)</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#414754]">
                                <span aria-hidden="true" class="material-symbols-outlined text-[#0058bc] text-base">check_circle</span>
                                <span>Profil Pengguna Publik dengan Foto Cover &amp; Avatar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 text-center text-xs text-[#727785] border-t border-[#e2e2e6] bg-white">
            &copy; {{ date('Y') }} SocialFeed MVP. Built with Laravel 11 &amp; Livewire 3.
        </footer>
    </body>
</html>
