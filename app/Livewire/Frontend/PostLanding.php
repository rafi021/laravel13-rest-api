<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Ai\Enums\Lab;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PostLanding extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedCategory = null;

    public function mount(): void
    {
        $this->search = request('q', '');
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
        return Post::query()
            ->with('category')
            ->when($this->search !== '', function ($query) {
                return $query->whereVectorSimilarTo(
                    'embedding',
                    Str::of($this->search)->toEmbeddings(
                        provider: Lab::Ollama,
                        model: 'mxbai-embed-large:latest'
                    ),
                    minSimilarity: 0.5
                );
            })
            ->when($this->selectedCategory, fn($query) => $query->where('category_id', $this->selectedCategory))
            ->unless($this->search !== '', fn($query) => $query->latest())
            ->paginate(5);
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
