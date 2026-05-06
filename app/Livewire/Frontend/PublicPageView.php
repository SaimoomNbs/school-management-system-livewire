<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicPageView extends Component
{
    public $page;

    public function mount($slug)
    {
        $this->page = DB::table('pages')
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        abort_unless($this->page, 404);
    }

    public function render()
    {
        return view('livewire.pages.frontend.public-page-view')
            ->layout('layouts.guest');
    }
}
