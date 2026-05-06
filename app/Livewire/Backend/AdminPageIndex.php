<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPageIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public bool $status = true;

    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255', // Usually requires unique check natively
            'content' => 'required|string',
            'status'  => 'required|boolean',
        ];
    }

    public function updatedTitle()
    {
        // Auto generate slug if creating newly
        if (!$this->editingId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function create()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $page = DB::table('pages')->where('id', $id)->first();
        if ($page) {
            $this->editingId = $page->id;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->status = (bool) $page->status;
            $this->showModal = true;
        }
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin']), 403);
        $this->validate();

        $data = [
            'title'      => $this->title,
            'slug'       => $this->slug,
            'content'    => $this->content,
            'status'     => $this->status,
            'updated_at' => now(),
        ];

        if ($this->editingId) {
            DB::table('pages')->where('id', $this->editingId)->update($data);
            session()->flash('success', 'Page updated.');
        } else {
            $data['created_at'] = now();
            DB::table('pages')->insert($data);
            session()->flash('success', 'Page created.');
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
            DB::table('pages')->where('id', $this->deletingId)->delete();
            session()->flash('success', 'Page deleted.');
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
        $this->slug = '';
        $this->content = '';
        $this->status = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $pages = DB::table('pages')
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
                                              ->orWhere('slug', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.pages.backend.admin-page-index', compact('pages'))
            ->layout('layouts.app');
    }
}
