<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdminGalleryIndex extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public string $category = '';
    public string $title = '';
    public $image; // new upload

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    // Header Settings
    public array $state = [
        'gallery_badge' => '✦ School Gallery',
        'gallery_title' => 'Life at Suzon Care Academy<span class="dot text-yellow-400">.</span>',
        'gallery_desc'  => 'A glimpse into our vibrant campus, modern facilities, and the joy of learning together.',
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
            'category' => 'required|string|max:100',
            'title'    => 'nullable|string|max:255',
            'image'    => 'required|image|max:5120', // max 5MB
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function saveSettings()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate([
            'state.gallery_badge' => 'nullable|string|max:255',
            'state.gallery_title' => 'nullable|string|max:255',
            'state.gallery_desc'  => 'nullable|string',
        ]);

        foreach ($this->state as $key => $val) {
            Setting::set($key, $val);
        }

        session()->flash('settings_success', 'Gallery section header updated successfully.');
    }

    public function create()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        $imgPath = $this->image->store('gallery', 'public');

        DB::table('galleries')->insert([
            'category'   => $this->category,
            'title'      => $this->title ?: 'Gallery Image',
            'image' => $imgPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->flash('success', 'Image uploaded successfully.');
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
            DB::table('galleries')->where('id', $this->deletingId)->delete();
            session()->flash('success', 'Image deleted from gallery.');
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
        $this->category = '';
        $this->title = '';
        $this->image = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $galleries = DB::table('galleries')
            ->when($this->search, fn($q) => $q->where('category', 'like', '%' . $this->search . '%')
                                              ->orWhere('title', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')
            ->paginate(12);
            
        $categories = DB::table('galleries')->select('category')->distinct()->pluck('category');

        return view('livewire.pages.backend.admin-gallery-index', compact('galleries', 'categories'))
            ->layout('layouts.app');
    }
}
