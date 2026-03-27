<article class="rounded-3xl border border-zinc-200/80 bg-white/80 p-8 shadow-sm backdrop-blur-sm lg:p-12">
    <p
        class="mb-4 inline-flex rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cyan-800">
        {{ $post->category?->name ?? 'General' }}
    </p>

    <h1 class="serif-display mb-4 text-3xl font-semibold leading-tight text-zinc-900 lg:text-5xl">
        {{ $post->title }}
    </h1>

    <div class="mb-8 flex flex-wrap items-center gap-3 text-sm text-zinc-600">
        <span>Published {{ $post->created_at->diffForHumans() }}</span>
        <span class="h-1 w-1 rounded-full bg-zinc-400" aria-hidden="true"></span>
        <span>{{ str_word_count($post->content) }} words</span>
    </div>

    <div class="prose prose-zinc max-w-none prose-headings:font-semibold prose-p:leading-8">
        {!! nl2br(e($post->content)) !!}
    </div>
</article>
