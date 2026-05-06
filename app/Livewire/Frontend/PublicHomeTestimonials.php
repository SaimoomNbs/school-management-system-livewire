<?php

namespace App\Livewire\Frontend;

use App\Models\Setting;
use App\Models\Testimonial;
use Livewire\Component;

class PublicHomeTestimonials extends Component
{
    public function render()
    {
        return view('livewire.pages.frontend.public-home-testimonials', [
            'testimonial_badge' => Setting::get('testimonial_badge', '✦ Testimonials & Results'),
            'testimonial_title' => Setting::get('testimonial_title', 'We value our students,<br/>let\'s hear from them<span class="text-yellow-400">.</span>'),
            'testimonials' => Testimonial::orderBy('sort_order')->latest()->get(),
        ])->layout('layouts.guest');
    }
}
