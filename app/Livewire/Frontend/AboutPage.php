<?php

namespace App\Livewire\Frontend;

use App\Models\Setting;
use Livewire\Component;

class AboutPage extends Component
{

    public function render()
    {
        return view('livewire.pages.frontend.about-page', [
            'about_badge' => Setting::get('about_badge', '✦ About Our School'),
            'about_title' => Setting::get('about_title', 'Building leaders of<br/>tomorrow<span class="dot"></span>'),
            'about_description' => Setting::get('about_description', 'Suzon Care Academy is a leading educational institution committed to academic excellence and holistic development. We provide a dynamic learning environment that nurtures curiosity, creativity, and character in every student.'),
            'about_mission_title' => Setting::get('about_mission_title', 'Our Mission'),
            'about_mission_desc' => Setting::get('about_mission_desc', 'To empower every student with knowledge, values, and skills to lead purposeful lives.'),
            'about_vision_title' => Setting::get('about_vision_title', 'Our Vision'),
            'about_vision_desc' => Setting::get('about_vision_desc', 'To be the most innovative and inclusive school transforming education in the region.'),
            'about_btn_text' => Setting::get('about_btn_text', 'Get Admission Info →'),
            'about_btn_link' => Setting::get('about_btn_link', '#contact'),
            'about_school_name' => Setting::get('about_school_name', 'Suzon Care Academy'),
            'about_school_established' => Setting::get('about_school_established', 'Established 1998'),
            'about_school_badge_value' => Setting::get('about_school_badge_value', '100%'),
            'about_school_badge_label' => Setting::get('about_school_badge_label', 'Scholarship Eligible'),
            'about_school_image_path' => Setting::get('about_school_image_path', '')
        ])->layout('layouts.guest');
    }
}
