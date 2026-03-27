<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Component;

class PostShow extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function render(): View
    {
        return view('livewire.frontend.post-show');
    }
}
