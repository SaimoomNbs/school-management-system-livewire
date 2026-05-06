<?php

namespace App\Livewire\Backend;

use App\Models\ContactMessage;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class AdminContactIndex extends Component
{
    use WithPagination;

    // Settings
    public array $state = [
        'contact_badge' => '✦ Get In Touch',
        'contact_title' => 'Start your journey<br/>today<span class="dot"></span>',
    ];

    // Bulk actions
    public array $selectedRows = [];
    public bool $selectAll = false;

    // Admin Note Edit
    public $editingId = null;
    public $admin_note = '';

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->state['contact_badge'] = Setting::get('contact_badge', $this->state['contact_badge']);
        $this->state['contact_title'] = Setting::get('contact_title', $this->state['contact_title']);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = ContactMessage::pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedRows = [];
        }
    }

    public function saveSettings()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate([
            'state.contact_badge' => 'required|string|max:255',
            'state.contact_title' => 'required|string',
        ]);

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('success', 'Contact section settings updated.');
    }

    public function deleteSelected()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        if (empty($this->selectedRows)) return;

        ContactMessage::whereIn('id', $this->selectedRows)->delete();
        $this->selectedRows = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected messages deleted.');
    }

    public function deleteSingle($id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        ContactMessage::destroy($id);
        session()->flash('success', 'Message deleted.');
    }

    public function editNote($id)
    {
        $contact = ContactMessage::findOrFail($id);
        $this->editingId = $contact->id;
        $this->admin_note = $contact->admin_note;
        $this->dispatch('open-modal', 'edit-note-modal');
    }

    public function saveNote()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $contact = ContactMessage::findOrFail($this->editingId);
        $contact->update(['admin_note' => $this->admin_note]);

        $this->editingId = null;
        $this->admin_note = '';
        $this->dispatch('close-modal', 'edit-note-modal');
        session()->flash('success', 'Admin note updated.');
    }

    public function render()
    {
        return view('livewire.pages.backend.admin-contact-index', [
            'messages' => ContactMessage::latest()->paginate(15)
        ])->layout('layouts.app');
    }
}
