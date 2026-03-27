<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | Blog</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|ibm-plex-serif:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body {
                font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif;
            }

            .serif-display {
                font-family: 'IBM Plex Serif', ui-serif, Georgia, serif;
            }

            .mesh-background {
                background-image:
                    radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.16), transparent 35%),
                    radial-gradient(circle at 80% 0%, rgba(16, 185, 129, 0.16), transparent 38%),
                    radial-gradient(circle at 10% 90%, rgba(14, 116, 144, 0.16), transparent 40%),
                    linear-gradient(135deg, #f7fafc, #f2f8f8);
            }
        </style>
    </head>
    <body class="mesh-background min-h-screen text-zinc-900 antialiased">
        <div class="mx-auto flex w-full max-w-7xl flex-col px-6 pb-14 pt-10 lg:px-10">
            <header class="mb-10 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight">
                    Instructory Blog
                </a>

                <nav class="flex items-center gap-3 text-sm font-medium">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-full border border-zinc-300 bg-white/70 px-4 py-2 transition hover:border-zinc-400 hover:bg-white">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-full border border-zinc-300 bg-white/70 px-4 py-2 transition hover:border-zinc-400 hover:bg-white">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-full bg-zinc-900 px-4 py-2 text-white transition hover:bg-zinc-800">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </header>

            <section class="mb-10 rounded-3xl border border-zinc-200/70 bg-white/70 p-8 backdrop-blur-md lg:p-10">
                <p class="mb-3 inline-flex rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.15em] text-cyan-800">
                    Editorial Insights
                </p>
                <h1 class="serif-display mb-4 max-w-3xl text-4xl font-semibold leading-tight text-zinc-900 lg:text-5xl">
                    Fresh Posts, Practical Thinking, and Clean Laravel Craft
                </h1>
                <p class="max-w-2xl text-base leading-relaxed text-zinc-600 lg:text-lg">
                    Explore our latest articles on development, architecture, and production-ready techniques. Each post is curated to be useful, actionable, and easy to apply.
                </p>
            </section>

            <livewire:frontend.post-landing :posts="$posts" />
        </div>

        @livewireScripts
    </body>
</html>
