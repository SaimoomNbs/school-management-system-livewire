<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminAboutSection extends Component
{
    use WithFileUploads;

    public $about_school_image_file;
    public array $state = [
        'about_badge'              => '✦ About Our School',
        'about_title'              => 'Building leaders of<br/>tomorrow<span class="dot"></span>',
        'about_description'        => 'Suzon Care Academy is a leading educational institution committed to academic excellence and holistic development. We provide a dynamic learning environment that nurtures curiosity, creativity, and character in every student.',
        'about_mission_title'      => 'Our Mission',
        'about_mission_desc'       => 'To empower every student with knowledge, values, and skills to lead purposeful lives.',
        'about_vision_title'       => 'Our Vision',
        'about_vision_desc'        => 'To be the most innovative and inclusive school transforming education in the region.',
        'about_btn_text'           => 'Get Admission Info →',
        'about_btn_link'           => '#contact',
        'about_school_name'        => 'Suzon Care Academy',
        'about_school_established' => 'Established 1998',
        'about_school_badge_value' => '100%',
        'about_school_badge_label' => 'Scholarship Eligible',
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
            'state.about_badge'              => 'nullable|string|max:255',
            'state.about_title'              => 'nullable|string|max:255',
            'state.about_description'        => 'nullable|string',
            'state.about_mission_title'      => 'nullable|string|max:255',
            'state.about_mission_desc'       => 'nullable|string',
            'state.about_vision_title'       => 'nullable|string|max:255',
            'state.about_vision_desc'        => 'nullable|string',
            'state.about_btn_text'           => 'nullable|string|max:50',
            'state.about_btn_link'           => 'nullable|string|max:255',
            'state.about_school_name'        => 'nullable|string|max:255',
            'state.about_school_established' => 'nullable|string|max:255',
            'state.about_school_badge_value' => 'nullable|string|max:50',
            'state.about_school_badge_label' => 'nullable|string|max:255',
            'about_school_image_file'        => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        if ($this->about_school_image_file) {
            $this->state['about_school_image_path'] = $this->about_school_image_file->store('settings', 'public');
        }

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'About section updated successfully.');
    }

    public function render()
    {
        return view('livewire.pages.backend.admin-about-section')->layout('layouts.app');
    }
}
