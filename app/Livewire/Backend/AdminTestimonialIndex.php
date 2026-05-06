<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use App\Models\Testimonial;
use Livewire\Component;

class AdminTestimonialIndex extends Component
{
    // Section Settings
    public array $state = [
        'testimonial_badge' => '✦ Testimonials & Results',
        'testimonial_title' => 'We value our students,<br/>let\'s hear from them<span class="text-yellow-400">.</span>',
    ];

    // CRUD properties
    public $testimonialId;
    public $name;
    public $subtitle;
    public $quote;
    public $rating = 5;
    public $sort_order = 0;
    
    public $isEditing = false;

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->state['testimonial_badge'] = Setting::get('testimonial_badge', $this->state['testimonial_badge']);
        $this->state['testimonial_title'] = Setting::get('testimonial_title', $this->state['testimonial_title']);
    }

    public function saveSettings()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate([
            'state.testimonial_badge' => 'required|string|max:255',
            'state.testimonial_title' => 'required|string',
        ]);

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'Header settings updated.');
    }

    public function saveTestimonial()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate([
            'name'     => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'quote'    => 'required|string',
            'rating'   => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer',
        ]);

        Testimonial::updateOrCreate(
            ['id' => $this->testimonialId],
            [
                'name'     => $this->name,
                'subtitle' => $this->subtitle,
                'quote'    => $this->quote,
                'rating'   => $this->rating,
                'sort_order' => $this->sort_order,
            ]
        );

        $this->resetForm();
        session()->flash('testimonial_success', $this->testimonialId ? 'Testimonial updated.' : 'Testimonial added.');
    }

    public function edit($id)
    {
        $testi = Testimonial::findOrFail($id);
        $this->testimonialId = $testi->id;
        $this->name = $testi->name;
        $this->subtitle = $testi->subtitle;
        $this->quote = $testi->quote;
        $this->rating = $testi->rating;
        $this->sort_order = $testi->sort_order;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        Testimonial::destroy($id);
        session()->flash('testimonial_success', 'Testimonial deleted.');
        if ($this->testimonialId == $id) $this->resetForm();
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->testimonialId = null;
        $this->name = '';
        $this->subtitle = '';
        $this->quote = '';
        $this->rating = 5;
        $this->sort_order = 0;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.pages.backend.admin-testimonial-index', [
            'testimonials' => Testimonial::orderBy('sort_order')->latest()->get()
        ])->layout('layouts.app');
    }
}
