<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamGroupIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterClassId = '';

    // Form
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $class_id = '';
    public string $title = '';
    public string $start_date = '';
    public string $end_date = '';

    // Delete
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterClassId() { $this->resetPage(); }

    protected function rules()
    {
        return [
            'class_id'   => 'required|exists:classes,id',
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ];
    }

    public function create()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'teacher']), 403);
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'teacher']), 403);
        $group = DB::table('exam_groups')->where('id', $id)->first();
        if ($group) {
            $this->editingId = $group->id;
            $this->class_id = (string) $group->class_id;
            $this->title = $group->title;
            // Handle potentially missing Carbon casting
            $this->start_date = substr($group->start_date, 0, 10);
            $this->end_date = substr($group->end_date, 0, 10);
            $this->showModal = true;
        }
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'teacher']), 403);
        $this->validate();

        $data = [
            'class_id'   => $this->class_id,
            'title'      => $this->title,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'updated_at' => now(),
        ];

        if ($this->editingId) {
            DB::table('exam_groups')->where('id', $this->editingId)->update($data);
            session()->flash('success', 'Exam group updated.');
        } else {
            $data['created_at'] = now();
            DB::table('exam_groups')->insert($data);
            session()->flash('success', 'Exam group created.');
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
            DB::table('exam_groups')->where('id', $this->deletingId)->delete();
            session()->flash('success', 'Exam group deleted.');
        }
        $this->closeDeleteModal();
        $this->resetPage();
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
        $this->class_id = '';
        $this->title = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();

        $groups = DB::table('exam_groups')
            ->join('classes', 'exam_groups.class_id', '=', 'classes.id')
            ->select('exam_groups.*', 'classes.name as class_name')
            ->when($this->search, fn($q) => $q->where('exam_groups.title', 'like', '%' . $this->search . '%'))
            ->when($this->filterClassId, fn($q) => $q->where('exam_groups.class_id', $this->filterClassId))
            ->orderByDesc('exam_groups.start_date')
            ->paginate(15);

        return view('livewire.pages.backend.admin-exam-group-index', compact('groups', 'allClasses'))
            ->layout('layouts.app');
    }
}
