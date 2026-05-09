<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PostLanding extends Component
{
    use WithPagination;

    public EloquentCollection $posts;

    public string $search = '';

    public ?int $selectedCategory = null;

    public function mount(EloquentCollection $posts): void
    {
        $this->posts = $posts;
    }

    #[Computed]
    public function topCategories()
    {
        return Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function filteredPosts()
    {
        if ($this->search !== '') {
            return Post::search($this->search)
                ->query(fn ($query) => $query
                    ->with('category')
                    ->when($this->selectedCategory, fn ($categoryQuery) => $categoryQuery->where('category_id', $this->selectedCategory))
                    ->latest())
                ->paginate(12);
        }

        return Post::query()
            ->with('category')
            ->when($this->selectedCategory, fn ($query) => $query->where('category_id', $this->selectedCategory))
            ->latest()
            ->paginate(12);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.frontend.post-landing', [
            'filteredPosts' => $this->filteredPosts(),
            'topCategories' => $this->topCategories(),
        ]);
    }
}
