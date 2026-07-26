<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SocialFeed') }} — Masuk</title>
        <meta name="theme-color" content="#1e1b4b">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[var(--bg-page)] text-[var(--text-primary)] font-sans antialiased min-h-screen">
        <main class="min-h-screen flex flex-col justify-center items-center py-12 px-4">
            <div class="max-w-[1100px] w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Left Side: Branding -->
                <section class="flex flex-col text-center lg:text-left space-y-4">
                    <a href="/" class="flex items-center justify-center lg:justify-start gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--accent)] flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-white text-2xl filled" style="font-variation-settings: 'FILL' 1">forum</span>
                        </div>
                        <h1 class="font-display font-extrabold text-4xl lg:text-5xl text-[var(--text-primary)] tracking-tight">
                            Social<span class="text-[var(--accent)]">Feed</span>
                        </h1>
                    </a>

                    <p class="text-lg lg:text-xl font-medium text-[var(--text-secondary)] max-w-md mx-auto lg:mx-0 leading-relaxed">
                        Terhubung dengan teman, bagikan momen, dan berdiskusi bersama komunitas.
                    </p>

                    <!-- Decorative Feature Cards -->
                    <div class="hidden lg:grid grid-cols-2 gap-4 pt-6">
                        <div class="card-elevation p-4 flex items-center gap-3">
                            <span aria-hidden="true" class="material-symbols-outlined text-2xl text-[var(--accent)]" style="font-variation-settings: 'FILL' 1">dynamic_feed</span>
                            <div>
                                <h4 class="font-bold text-xs text-[var(--text-primary)]">Feed Realtime</h4>
                                <p class="text-[11px] text-[var(--text-secondary)]">Update status & berita terbaru</p>
                            </div>
                        </div>

                        <div class="card-elevation p-4 flex items-center gap-3">
                            <span aria-hidden="true" class="material-symbols-outlined text-2xl text-[var(--accent)]" style="font-variation-settings: 'FILL' 1">chat_bubble</span>
                            <div>
                                <h4 class="font-bold text-xs text-[var(--text-primary)]">Diskusi Interaktif</h4>
                                <p class="text-[11px] text-[var(--text-secondary)]">Komentar bersarang & likes</p>
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
