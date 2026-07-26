<!DOCTYPE html>
<html lang="id" x-data="{ dark: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('dark', val => { localStorage.setItem('darkMode', val); document.documentElement.classList.toggle('dark', val) })" :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- SEO --}}
        <title>@yield('title', config('app.name', 'SocialFeed'))</title>
        <meta name="description" content="@yield('description', 'SocialFeed — Ruang Berbagi Indonesia. Platform media sosial untuk berbagi postingan, foto, cerita, dan berinteraksi dengan komunitas.')">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url()->current() }}">

        {{-- Open Graph --}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('title', config('app.name', 'SocialFeed'))">
        <meta property="og:description" content="@yield('description', 'SocialFeed — Ruang Berbagi Indonesia.')">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="SocialFeed">
        <meta property="og:locale" content="id_ID">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', config('app.name', 'SocialFeed'))">
        <meta name="twitter:description" content="@yield('description', 'SocialFeed — Ruang Berbagi Indonesia.')">
        <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

        {{-- PWA --}}
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#1e1b4b">

        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <script>if(localStorage.getItem('darkMode')==='true'){document.documentElement.classList.add('dark')}</script>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }
        </script>
    </head>
    <body class="font-sans antialiased min-h-screen" x-data="{ toast: null, toastType: 'info' }" @notify.window="toast = $event.detail.message; toastType = $event.detail.type || 'info'; setTimeout(() => toast = null, 3500)">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <livewire:layout.navigation />

            <!-- Sentinel for floating search IntersectionObserver -->
            <div id="header-sentinel" class="w-full h-px"></div>

            <!-- Floating Search Bar (hidden until nav scrolls out) -->
            <div id="floating-search" class="hidden fixed left-1/2 -translate-x-1/2 top-4 z-50 w-[min(720px,92%)] sm:w-3/4 md:w-1/2 lg:w-1/3">
                <div class="bg-[var(--ivory-warm)] shadow-lg rounded-full p-2.5 flex items-center gap-2 border border-[var(--card-border)]">
                    <x-search class="w-full flex items-center gap-2" />
                </div>
            </div>

            <!-- 3-Column Container -->
            <main class="flex-1 max-w-[1280px] w-full mx-auto px-3 sm:px-4 py-3 sm:py-4">
                <div class="grid grid-cols-12 gap-4">
                    
                    <!-- Left Sidebar (3 cols, sticky, self-scrolling) -->
                    @auth
                    <aside class="col-span-3 hidden lg:block">
                        <div class="sticky top-16 max-h-[calc(100vh-4rem)] overflow-y-auto">
                            <x-left-sidebar />
                        </div>
                    </aside>
                    @endauth

                    <!-- Main Feed Container (6 cols, scrolls naturally) -->
                    <section class="col-span-12 lg:col-span-6 space-y-3">
                        <!-- Community Pulse Signature -->
                        <div class="card-elevation overflow-hidden">
                            <div class="pulse-bar h-0.5 bg-gradient-to-r from-[var(--accent)] via-[var(--gold)] to-[var(--accent)]"></div>
                            <div class="px-3 py-1.5 flex items-center justify-between text-[11px]">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--accent)] opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[var(--accent)]"></span>
                                    </span>
                                    <span class="font-semibold text-[var(--text-variant)]">SocialFeed <span class="hidden sm:inline">— Ruang Berbagi Indonesia</span></span>
                                </div>
                                <span class="text-[var(--text-secondary)]">
                                    @php $online = random_int(12, 47); @endphp
                                    <span class="font-semibold text-[var(--accent)]">{{ $online }}</span> orang online
                                </span>
                            </div>
                        </div>

                        @if (isset($header))
                            <div class="card-elevation p-3">
                                {{ $header }}
                            </div>
                        @endif

                        {{ $slot }}
                    </section>

                    <!-- Right Sidebar (3 cols, sticky, self-scrolling) -->
                    @auth
                    <aside class="col-span-3 hidden lg:block">
                        <div class="sticky top-16 max-h-[calc(100vh-4rem)] overflow-y-auto">
                            <x-right-sidebar />
                        </div>
                    </aside>
                    @endauth

                </div>
            </main>
        </div>

        <template x-teleport="body">
            <div x-show="toast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] px-5 py-3 rounded-xl shadow-lg text-sm font-semibold flex items-center gap-2" :class="toastType === 'error' ? 'bg-red-600 text-white' : 'dark:bg-gray-800 bg-[var(--text-primary)] text-white'" x-text="toast"></div>
        </template>
    </body>
</html>

