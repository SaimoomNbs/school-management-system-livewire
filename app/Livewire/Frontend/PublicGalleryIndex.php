<?php

namespace App\Livewire\Frontend;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicGalleryIndex extends Component
{
    public string $filterCategory = '';

    public function render()
    {
        $galleriesQuery = DB::table('galleries')->orderBy('created_at', 'desc');
        
        if ($this->filterCategory) {
            $galleriesQuery->where('category', $this->filterCategory);
        }

        $galleries = $galleriesQuery->paginate(8);
        $categories = DB::table('galleries')->select('category')->distinct()->pluck('category');

        return view('livewire.pages.frontend.public-gallery-index', [
            'galleries' => $galleries,
            'categories' => $categories,
            'gallery_badge' => Setting::get('gallery_badge', '✦ School Gallery'),
            'gallery_title' => Setting::get('gallery_title', 'Life at Suzon Care Academy<span class="dot text-yellow-400">.</span>'),
            'gallery_desc'  => Setting::get('gallery_desc', 'A glimpse into our vibrant campus, modern facilities, and the joy of learning together.'),
        ])->layout('layouts.guest');
    }
}
