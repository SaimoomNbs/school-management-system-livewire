<?php

namespace App\Livewire\Frontend;

use App\Models\ContactMessage;
use App\Models\Setting;
use Livewire\Component;

class PublicHomeContact extends Component
{
    // Form fields
    public $name;
    public $phone;
    public $message;

    // Content Settings
    public $badge;
    public $title;
    public $address;
    public $phone_setting;
    public $email_setting;
    public $google_map_iframe;

    public function mount()
    {
        $this->badge = Setting::get('contact_badge', '✦ Get In Touch');
        $this->title = Setting::get('contact_title', 'Start your journey<br/>today<span class="dot"></span>');
        
        $this->address = Setting::get('address', '123 Academy Road, Dhanmondi, Dhaka-1205, Bangladesh');
        $this->phone_setting = Setting::get('phone', '+880 1800-Suzon Care Academy');
        $this->email_setting = Setting::get('email', 'info@Suzon Care Academyacademy.edu.bd');
        $this->google_map_iframe = Setting::get('google_map_iframe');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        ContactMessage::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'phone', 'message']);

        session()->flash('success', 'Thank you! Your message has been sent successfully.');
    }

    public function render()
    {
        return view('livewire.pages.frontend.public-home-contact')->layout('layouts.guest');
    }
}
