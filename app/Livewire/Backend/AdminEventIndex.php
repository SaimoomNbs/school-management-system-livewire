<?php

namespace App\Livewire\Backend;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminEventIndex extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $description = '';
    public string $event_date = '';
    public $image; // file upload
    public ?string $existing_image = null;

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    // Header Settings
    public array $state = [
        'event_badge' => '✦ All Events',
        'event_title' => 'Upcoming & Past Events<span class="dot text-yellow-400">.</span>',
        'event_desc'  => 'Discover our extracurricular activities and institutional milestones.',
    ];

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        foreach (array_keys($this->state) as $key) {
            $this->state[$key] = Setting::get($key, $this->state[$key]);
        }
    }

    protected function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'image'       => 'nullable|image|max:2048', // optional max 2MB
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function saveSettings()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate([
            'state.event_badge' => 'nullable|string|max:255',
            'state.event_title' => 'nullable|string|max:255',
            'state.event_desc'  => 'nullable|string',
        ]);

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('settings_success', 'Event section header updated successfully.');
    }

    public function create()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $event = DB::table('events')->where('id', $id)->first();
        if ($event) {
            $this->editingId = $event->id;
            $this->title = $event->title;
            $this->description = $event->description;
            $this->event_date = substr($event->event_date, 0, 10);
            $this->existing_image = $event->image;
            $this->showModal = true;
        }
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        $imgPath = $this->existing_image;
        if ($this->image) {
            $imgPath = $this->image->store('events', 'public');
        }

        $data = [
            'title'       => $this->title,
            'description' => $this->description,
            'event_date'  => $this->event_date,
            'image'       => $imgPath,
            'updated_at'  => now(),
        ];

        if ($this->editingId) {
            DB::table('events')->where('id', $this->editingId)->update($data);
            session()->flash('success', 'Event updated successfully.');
        } else {
            $data['created_at'] = now();
            DB::table('events')->insert($data);
            session()->flash('success', 'Event created successfully.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function confirmDelete(int $id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        if ($this->deletingId) {
            DB::table('events')->where('id', $this->deletingId)->delete();
            session()->flash('success', 'Event deleted.');
        }
        $this->closeDeleteModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->description = '';
        $this->event_date = '';
        $this->image = null;
        $this->existing_image = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $events = DB::table('events')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->orderByDesc('event_date')
            ->paginate(12);

        return view('livewire.pages.backend.admin-event-index', compact('events'))
            ->layout('layouts.app');
    }
}
