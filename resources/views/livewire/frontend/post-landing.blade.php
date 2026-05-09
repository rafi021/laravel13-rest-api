<section class="space-y-8">
    <!-- Search Bar -->
    <div class="rounded-2xl border border-zinc-200/70 bg-white/70 p-6 backdrop-blur-md">
        <div class="flex flex-col gap-3">
            <label for="search" class="text-sm font-semibold text-zinc-700">Search Posts</label>
            <input type="text" id="search" wire:model.live="search" placeholder="Search by title or content..."
                class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm placeholder-zinc-500 transition focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
        <!-- Left Sidebar: Filters -->
        <aside class="space-y-6 lg:col-span-1">
            <!-- Category Filter -->
            <div class="rounded-2xl border border-zinc-200/70 bg-white/70 p-6 backdrop-blur-md">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-900">Filter by Category</h3>

                <div class="space-y-3">
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="radio" name="category" value="" wire:model.live="selectedCategory"
                            class="h-4 w-4 border-zinc-300 text-cyan-600 focus:ring-cyan-500" />
                        <span class="text-sm text-zinc-700">All Categories</span>
                    </label>

                    @foreach ($topCategories as $category)
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="radio" name="category" value="{{ $category->id }}"
                                wire:model.live="selectedCategory"
                                class="h-4 w-4 border-zinc-300 text-cyan-600 focus:ring-cyan-500" />
                            <span class="text-sm text-zinc-700">{{ $category->name }}</span>
                            <span
                                class="ml-auto text-xs font-medium text-zinc-500">({{ $category->posts_count }})</span>
                        </label>
                    @endforeach
                </div>

                @if ($search || $selectedCategory)
                    <button wire:click="clearFilters"
                        class="mt-4 w-full rounded-lg bg-zinc-100 py-2 text-xs font-semibold text-zinc-700 transition hover:bg-zinc-200">
                        Clear Filters
                    </button>
                @endif
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">
                        @if ($search)
                            Search Results
                        @elseif ($selectedCategory)
                            Filtered Posts
                        @else
                            Latest Posts
                        @endif
                    </h2>
                    <p class="text-sm text-zinc-600">{{ $filteredPosts->count() }} articles</p>
                </div>

                @if ($filteredPosts->isEmpty())
                    <div class="rounded-2xl border border-zinc-200 bg-white/80 p-8 text-center">
                        <p class="text-lg font-medium text-zinc-800">No posts found.</p>
                        <p class="mt-2 text-sm text-zinc-600">
                            @if ($search)
                                Try a different search term or browse all categories.
                            @else
                                Create a few posts from your dashboard and they will appear here.
                            @endif
                        </p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($filteredPosts as $post)
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

                    <!-- Pagination -->
                    @if ($filteredPosts->hasPages())
                        <div class="mt-8">
                            {{ $filteredPosts->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Right Sidebar: Top Categories -->
        <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-zinc-200/70 bg-white/70 p-6 backdrop-blur-md sticky top-20">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-900">Top Categories</h3>

                <div class="space-y-3">
                    @forelse ($topCategories as $category)
                        <button wire:click="$set('selectedCategory', {{ $category->id }})"
                            class="block w-full rounded-lg border border-transparent bg-zinc-50 p-3 text-left transition hover:border-cyan-300 hover:bg-cyan-50">
                            <p class="text-sm font-medium text-zinc-900">{{ $category->name }}</p>
                            <p class="text-xs text-zinc-600">{{ $category->posts_count }}
                                {{ Illuminate\Support\Str::plural('post', $category->posts_count) }}</p>
                        </button>
                    @empty
                        <p class="text-sm text-zinc-600">No categories yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</section>
