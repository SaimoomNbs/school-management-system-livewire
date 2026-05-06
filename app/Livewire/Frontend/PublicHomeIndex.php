<?php

namespace App\Livewire\Frontend;

use App\Models\Setting;
use App\Models\FeatureCard;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicHomeIndex extends Component
{
    public function render()
    {
        // Get the first active page to show in the page view section
        $firstPage = DB::table('pages')->where('status', 1)->first();
        
        return view('livewire.pages.frontend.public-home-index', [
            'firstPageSlug' => $firstPage?->slug,
            'hero_notice' => Setting::get('hero_notice', '✦ Admissions Open 2025–26'),
            'hero_title' => Setting::get('hero_title', 'Shape your child\'s<br/><span class="text-teal-700">future</span> with us<span class="dot"></span>'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'A nurturing environment where young minds thrive academically, socially, and creatively — every single day.'),
            'hero_btn1_text' => Setting::get('hero_btn1_text', 'Apply Now →'),
            'hero_btn1_link' => Setting::get('hero_btn1_link', '#contact'),
            'hero_btn2_text' => Setting::get('hero_btn2_text', 'Learn More'),
            'hero_btn2_link' => Setting::get('hero_btn2_link', '#about'),
            'hero_stats_years' => Setting::get('hero_stats_years', '26+'),
            'hero_stats_students' => Setting::get('hero_stats_students', '3,200'),
            'hero_stats_teachers' => Setting::get('hero_stats_teachers', '180+'),
            'hero_stats_pass_rate' => Setting::get('hero_stats_pass_rate', '96%'),

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
            'about_school_image_path' => Setting::get('about_school_image_path', ''),

            'why_badge' => Setting::get('why_badge', '✦ Why Choose Us'),
            'why_title' => Setting::get('why_title', 'We offer more than just<br/><span class="text-yellow-400">an education</span><span class="text-yellow-400">.</span>'),
            'feature_cards' => FeatureCard::orderBy('sort_order')->orderBy('created_at')->get(),
        ])->layout('layouts.guest');
    }
}
