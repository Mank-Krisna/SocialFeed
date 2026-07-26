<!DOCTYPE html>
<html class="light" lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SocialFeed') }} - Connect with the World</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f9f9fd] text-[#1a1c1f] font-sans antialiased min-h-screen">
        <main class="min-h-screen flex flex-col justify-center items-center py-12 px-4">
            <div class="max-w-[1100px] w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Side: Branding -->
                <section class="flex flex-col text-center lg:text-left space-y-4">
                    <div class="flex items-center justify-center lg:justify-start gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="SocialFeed Logo" width="56" height="56" class="w-14 h-14 rounded-2xl object-cover shadow-md border border-[#e2e2e6]">
                        <h1 class="text-4xl lg:text-5xl font-extrabold text-[#0058bc] tracking-tight font-display">
                            SocialFeed
                        </h1>
                    </div>

                    <p class="text-lg lg:text-xl font-medium text-[#414754] max-w-md mx-auto lg:mx-0 leading-relaxed">
                        Terhubung dengan teman, bagikan momen, dan berdiskusi bersama komunitas.
                    </p>

                    <!-- Decorative Feature Cards -->
                    <div class="hidden lg:grid grid-cols-2 gap-4 pt-6">
                        <div class="card-elevation p-4 flex items-center gap-3">
                            <span aria-hidden="true" class="material-symbols-outlined text-2xl text-[#0058bc]">dynamic_feed</span>
                            <div>
                                <h4 class="font-bold text-xs text-[#1a1c1f]">Feed Realtime</h4>
                                <p class="text-[11px] text-[#727785]">Update status & berita terbaru</p>
                            </div>
                        </div>

                        <div class="card-elevation p-4 flex items-center gap-3">
                            <span aria-hidden="true" class="material-symbols-outlined text-2xl text-[#0058bc]">chat_bubble</span>
                            <div>
                                <h4 class="font-bold text-xs text-[#1a1c1f]">Diskusi Interaktif</h4>
                                <p class="text-[11px] text-[#727785]">Komentar bersarang & likes</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right Side: Auth Card Container -->
                <section class="flex justify-center lg:justify-end">
                    <div class="w-full max-w-[420px] p-4 sm:p-8 card-elevation">
                        {{ $slot }}
                    </div>
                </section>

            </div>
        </main>
    </body>
</html>
