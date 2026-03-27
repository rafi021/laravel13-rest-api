<x-layouts::app :title="__('Posts')">
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ __('Posts') }}</h1>
            <a href="{{ route('posts.create') }}"
                class="inline-flex items-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                {{ __('Add Post') }}
            </a>
        </div>

        @if (session('success'))
            <div
                class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Title') }}</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Category') }}</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Created') }}</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse ($posts as $post)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $post->title }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $post->category->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $post->created_at->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <a href="{{ route('posts.edit', $post) }}"
                                    class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ __('Edit') }}</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('{{ __('Are you sure you want to delete this post?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="ml-3 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No posts found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $posts->links() }}</div>
    </div>
</x-layouts::app>
