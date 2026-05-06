<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminSettingsForm extends Component
{
    use WithFileUploads;

    public $logo_file;
    public $favicon_file;
    public array $state = [
        'academy_name'       => '',
        'address'            => '',
        'phone'              => '',
        'email'              => '',
        'currency_symbol'    => '৳',
        'logo_path'          => '',
        'favicon_path'       => '',
        'fb_link'            => '',
        'youtube_link'       => '',
        'footer_description' => '',
        'google_map_iframe'  => '',
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
            'state.academy_name'       => 'required|string|max:255',
            'state.address'            => 'nullable|string',
            'state.phone'              => 'nullable|string',
            'state.email'              => 'nullable|email',
            'state.currency_symbol'    => 'required|string|max:10',
            'state.logo_path'          => 'nullable|string|max:255',
            'state.favicon_path'       => 'nullable|string|max:255',
            'state.fb_link'            => 'nullable|url',
            'state.youtube_link'       => 'nullable|url',
            'state.footer_description' => 'nullable|string',
            'state.google_map_iframe'  => 'nullable|string',
            'logo_file'                => 'nullable|image|max:2048',
            'favicon_file'             => 'nullable|image|max:1024',
        ];
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        if ($this->logo_file) {
            $this->state['logo_path'] = $this->logo_file->store('settings', 'public');
        }

        if ($this->favicon_file) {
            $this->state['favicon_path'] = $this->favicon_file->store('settings', 'public');
        }

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'Global settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.pages.backend.admin-settings-form')->layout('layouts.app');
    }
}
