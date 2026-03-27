<x-layouts::app :title="__('Edit Category')">
    <div class="mx-auto max-w-2xl space-y-6">
        <h1 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ __('Edit Category') }}</h1>

        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name"
                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm focus:border-zinc-500 focus:outline-none focus:ring-1 focus:ring-zinc-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:focus:border-zinc-400 dark:focus:ring-zinc-400" />
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    {{ __('Update') }}
                </button>
                <a href="{{ route('categories.index') }}"
                    class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-layouts::app>
