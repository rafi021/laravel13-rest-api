<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $post->title }} | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|ibm-plex-serif:400,500,600"
        rel="stylesheet" />

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
                radial-gradient(circle at 15% 10%, rgba(6, 182, 212, 0.15), transparent 33%),
                radial-gradient(circle at 85% 5%, rgba(34, 197, 94, 0.14), transparent 36%),
                linear-gradient(120deg, #f9fbfc, #f4f9f8);
        }
    </style>
</head>

<body class="mesh-background min-h-screen text-zinc-900 antialiased">
    <div class="mx-auto max-w-4xl px-6 py-10 lg:px-10">
        <a href="{{ route('home') }}"
            class="mb-8 inline-flex items-center gap-2 rounded-full border border-zinc-300 bg-white/70 px-4 py-2 text-sm font-medium transition hover:border-zinc-400 hover:bg-white">
            <span aria-hidden="true">&larr;</span>
            Back to posts
        </a>

        <livewire:frontend.post-show :post="$post" />
    </div>

    @livewireScripts
</body>

</html>
