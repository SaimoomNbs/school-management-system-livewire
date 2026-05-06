<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminResultEntry extends Component
{
    use WithFileUploads;

    public string $class_id      = '';
    public string $exam_group_id = '';
    public string $exam_id       = '';

    public bool  $sheetLoaded = false;
    public array $rows        = [];
    public ?object $currentExam = null;

    public function updatedClassId()
    {
        $this->exam_group_id = '';
        $this->exam_id       = '';
        $this->sheetLoaded   = false;
        $this->rows          = [];
    }

    public function updatedExamGroupId()
    {
        $this->exam_id     = '';
        $this->sheetLoaded = false;
        $this->rows        = [];
    }

    public function updatedExamId()
    {
        $this->sheetLoaded = false;
        $this->rows        = [];
        $this->currentExam = DB::table('exams')->where('id', $this->exam_id)->first();
    }

    public function loadSheet()
    {
        $this->validate([
            'class_id'      => 'required|exists:classes,id',
            'exam_group_id' => 'required|exists:exam_groups,id',
            'exam_id'       => 'required|exists:exams,id',
        ]);

        $this->currentExam = DB::table('exams')->where('id', $this->exam_id)->first();
        if (!$this->currentExam) return;

        $students = Student::where('class_id', $this->class_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        if ($students->isEmpty()) {
            session()->flash('error', 'No active students found in this class.');
            return;
        }

        $existingResults = DB::table('results')
            ->where('exam_id', $this->exam_id)
            ->get()
            ->keyBy('student_id');

        $this->rows = $students->map(function($s) use ($existingResults) {
            $existing = $existingResults->get($s->id);
            return [
                'student_id'   => $s->id,
                'name'         => $s->name,
                'student_code' => $s->student_id,
                'photo'        => $s->photo,
                'marks'        => $existing ? $existing->marks : '',
                'remarks'      => $existing ? $existing->remarks : '',
                'grade'        => $existing ? $existing->grade : '',
                'attachment'   => null,
                'existing_attachment' => $existing ? $existing->attachment : null,
            ];
        })->toArray();

        $this->sheetLoaded = true;
    }

    public function autoCalculateGrades()
    {
        if (!$this->currentExam) return;
        $totalMarks = (float) $this->currentExam->total_marks;

        foreach ($this->rows as $index => $row) {
            if ($row['marks'] === '' || $row['marks'] === null) continue;

            $marks = (float) $row['marks'];
            if ($totalMarks <= 0) continue;

            $pct = ($marks / $totalMarks) * 100;
            
            if ($pct >= 90)     $grade = 'A+';
            elseif ($pct >= 80) $grade = 'A';
            elseif ($pct >= 70) $grade = 'B';
            elseif ($pct >= 60) $grade = 'C';
            elseif ($pct >= 50) $grade = 'D';
            elseif ($pct >= 33) $grade = 'E'; // E stands for 33-49 passing margin
            else                $grade = 'F';

            $this->rows[$index]['grade'] = $grade;
        }
    }

    public function save()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'teacher']), 403);

        if (!$this->sheetLoaded || empty($this->rows)) {
            session()->flash('error', 'Please load the sheet and enter data first.');
            return;
        }

        $validData = [];
        foreach ($this->rows as $row) {
            // Only save rows where marks are actually inputted
            if ($row['marks'] !== '') {
                $validData[] = [
                    'exam_id'    => (int) $this->exam_id,
                    'student_id' => (int) $row['student_id'],
                    'marks'      => (float) $row['marks'],
                    'grade'      => $row['grade'] ?? null,
                    'remarks'    => !empty($row['remarks']) ? $row['remarks'] : null,
                    'attachment' => !empty($row['attachment']) ? $row['attachment']->store('results', 'public') : ($row['existing_attachment'] ?? null),
                ];
            }
        }

        if (empty($validData)) {
            session()->flash('warning', 'No marks entered to save.');
            return;
        }

        // MySQL Upsert matching unique constraint (exam_id, student_id)
        DB::table('results')->upsert(
            $validData,
            ['exam_id', 'student_id'],
            ['marks', 'grade', 'remarks', 'attachment']
        );

        $examTitle = $this->currentExam ? $this->currentExam->title : 'An exam';
        foreach ($validData as $resPayload) {
            NotificationService::send(
                'result_published',
                'Exam Result Published',
                "Your result for {$examTitle} has been published. Grade: " . ($resPayload['grade'] ?? 'N/A'),
                'App\Models\Student',
                $resPayload['student_id']
            );
        }

        session()->flash('success', 'Saved results for ' . count($validData) . ' student(s).');
        $this->redirect(route('admin.results.entry'), navigate: true);
    }

    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();
        
        $examGroups = $this->class_id
            ? DB::table('exam_groups')->where('class_id', $this->class_id)->orderByDesc('start_date')->get()
            : collect();
            
        $exams = $this->exam_group_id
            ? DB::table('exams')
                ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
                ->where('exams.exam_group_id', $this->exam_group_id)
                ->select('exams.id', 'exams.title', 'subjects.name as subject_name')
                ->orderBy('subjects.name')
                ->get()
            : collect();

        return view('livewire.pages.backend.admin-result-entry', compact('allClasses', 'examGroups', 'exams'))
            ->layout('layouts.app');
    }
}