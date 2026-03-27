<section class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">Latest Posts</h2>
        <p class="text-sm text-zinc-600">{{ $posts->count() }} articles</p>
    </div>

    @if ($posts->isEmpty())
        <div class="rounded-2xl border border-zinc-200 bg-white/80 p-8 text-center">
            <p class="text-lg font-medium text-zinc-800">No posts have been published yet.</p>
            <p class="mt-2 text-sm text-zinc-600">Create a few posts from your dashboard and they will appear here.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($posts as $post)
                <article wire:key="post-card-{{ $post->id }}"
                    class="group rounded-2xl border border-zinc-200/80 bg-white/80 p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-cyan-300 hover:shadow-lg">
                    <p
                        class="mb-3 inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-700">
                        {{ $post->category?->name ?? 'General' }}
                    </p>

                    <h3 class="mb-3 line-clamp-2 text-xl font-semibold leading-tight text-zinc-900">
                        <a href="{{ route('front.posts.show', $post) }}"
                            class="decoration-cyan-500 decoration-2 underline-offset-4 transition hover:underline">
                            {{ $post->title }}
                        </a>
                    </h3>

                    <p class="mb-6 line-clamp-3 text-sm leading-relaxed text-zinc-600">
                        {{ \Illuminate\Support\Str::limit($post->content, 140) }}
                    </p>

                    <a href="{{ route('front.posts.show', $post) }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-cyan-700 transition group-hover:text-cyan-800">
                        Read article
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">&rarr;</span>
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</section>
