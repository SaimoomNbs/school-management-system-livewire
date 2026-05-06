<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use Livewire\Component;

class AdminHeroSection extends Component
{
    public array $state = [
        'hero_notice'          => '✦ Admissions Open 2025–26',
        'hero_title'           => 'Shape your child\'s<br/><span class="text-teal-700">future</span> with us<span class="dot"></span>',
        'hero_subtitle'        => 'A nurturing environment where young minds thrive academically, socially, and creatively — every single day.',
        'hero_btn1_text'       => 'Apply Now →',
        'hero_btn1_link'       => '#contact',
        'hero_btn2_text'       => 'Learn More',
        'hero_btn2_link'       => '#about',
        'hero_stats_years'     => '26+',
        'hero_stats_students'  => '3,200',
        'hero_stats_teachers'  => '180+',
        'hero_stats_pass_rate' => '96%',
    ];

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        // Load initial keys
        foreach (array_keys($this->state) as $key) {
            $this->state[$key] = Setting::get($key, $this->state[$key]);
        }
    }

    protected function rules()
    {
        return [
            'state.hero_notice'          => 'nullable|string|max:255',
            'state.hero_title'           => 'nullable|string|max:255',
            'state.hero_subtitle'        => 'nullable|string',
            'state.hero_btn1_text'       => 'nullable|string|max:50',
            'state.hero_btn1_link'       => 'nullable|string|max:255',
            'state.hero_btn2_text'       => 'nullable|string|max:50',
            'state.hero_btn2_link'       => 'nullable|string|max:255',
            'state.hero_stats_years'     => 'nullable|string|max:50',
            'state.hero_stats_students'  => 'nullable|string|max:50',
            'state.hero_stats_teachers'  => 'nullable|string|max:50',
            'state.hero_stats_pass_rate' => 'nullable|string|max:50',
        ];
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'Hero section updated successfully.');
    }

    public function render()
    {
        return view('livewire.pages.backend.admin-hero-section')->layout('layouts.app');
    }
}
