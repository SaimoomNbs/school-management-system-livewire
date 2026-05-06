<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterClassId = '';
    public string $filterGroupId = '';

    // Form
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $exam_group_id = '';
    public string $subject_id    = '';
    public string $title         = '';
    public string $exam_date     = '';
    public string $total_marks   = '100';
    public string $pass_marks    = '33';

    // Dependent Form States
    public string $formClassId = '';

    // Delete
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterClassId() { $this->filterGroupId = ''; $this->resetPage(); }
    public function updatingFilterGroupId() { $this->resetPage(); }
    
    public function updatedFormClassId() {
        $this->exam_group_id = '';
        $this->subject_id = '';
    }

    protected function rules()
    {
        return [
            'exam_group_id' => 'required|exists:exam_groups,id',
            'subject_id'    => 'required|exists:subjects,id',
            'title'         => 'required|string|max:255',
            'exam_date'     => 'required|date',
            'total_marks'   => 'required|numeric|min:1',
            'pass_marks'    => 'required|numeric|min:0|lte:total_marks',
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
        $exam = DB::table('exams')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->select('exams.*', 'exam_groups.class_id')
            ->where('exams.id', $id)
            ->first();

        if ($exam) {
            $this->editingId = $exam->id;
            $this->formClassId = (string) $exam->class_id;
            $this->exam_group_id = (string) $exam->exam_group_id;
            $this->subject_id = (string) $exam->subject_id;
            $this->title = $exam->title;
            $this->exam_date = substr($exam->exam_date, 0, 10);
            $this->total_marks = (string) $exam->total_marks;
            $this->pass_marks = (string) $exam->pass_marks;
            $this->showModal = true;
        }
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'teacher']), 403);
        $this->validate();

        $data = [
            'exam_group_id' => $this->exam_group_id,
            'subject_id'    => $this->subject_id,
            'title'         => $this->title,
            'exam_date'     => $this->exam_date,
            'total_marks'   => $this->total_marks,
            'pass_marks'    => $this->pass_marks,
            'updated_at'    => now(),
        ];

        if ($this->editingId) {
            DB::table('exams')->where('id', $this->editingId)->update($data);
            session()->flash('success', 'Exam updated.');
        } else {
            $data['created_at'] = now();
            DB::table('exams')->insert($data);
            session()->flash('success', 'Exam created.');
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
            DB::table('exams')->where('id', $this->deletingId)->delete();
            session()->flash('success', 'Exam deleted.');
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
        $this->formClassId = '';
        $this->exam_group_id = '';
        $this->subject_id = '';
        $this->title = '';
        $this->exam_date = now()->format('Y-m-d');
        $this->total_marks = '100';
        $this->pass_marks = '33';
        $this->resetErrorBag();
    }

    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();
        
        $filterGroups = $this->filterClassId 
            ? DB::table('exam_groups')->where('class_id', $this->filterClassId)->orderByDesc('start_date')->get()
            : collect();

        // Form dependencies
        $formGroups = $this->formClassId 
            ? DB::table('exam_groups')->where('class_id', $this->formClassId)->orderByDesc('start_date')->get()
            : collect();
            
        $formSubjects = $this->formClassId 
            ? DB::table('subjects')->where('class_id', $this->formClassId)->orderBy('name')->get()
            : collect();

        $exams = DB::table('exams')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->join('classes', 'exam_groups.class_id', '=', 'classes.id')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->select(
                'exams.*',
                'exam_groups.title as group_title',
                'classes.name as class_name',
                'subjects.name as subject_name'
            )
            ->when($this->search, fn($q) => $q->where('exams.title', 'like', '%' . $this->search . '%')
                                              ->orWhere('subjects.name', 'like', '%' . $this->search . '%'))
            ->when($this->filterClassId, fn($q) => $q->where('exam_groups.class_id', $this->filterClassId))
            ->when($this->filterGroupId, fn($q) => $q->where('exams.exam_group_id', $this->filterGroupId))
            ->orderByDesc('exams.exam_date')
            ->paginate(15);

        return view('livewire.pages.backend.admin-exam-index', compact('exams', 'allClasses', 'filterGroups', 'formGroups', 'formSubjects'))
            ->layout('layouts.app');
    }
}
