<?php

namespace App\Livewire\Frontend;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class PostLanding extends Component
{
    public Collection $posts;

    public function mount(Collection $posts): void
    {
        $this->posts = $posts;
    }

    public function render(): View
    {
        return view('livewire.frontend.post-landing');
    }
}
