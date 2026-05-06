<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentMySubjects extends Component
{
    public function render()
    {
        $student = auth()->user()?->student;
        abort_unless($student, 403);

        $subjects = DB::table('subjects')
            ->leftJoin('teachers', 'subjects.teacher_id', '=', 'teachers.id')
            ->where('subjects.class_id', $student->class_id)
            ->select('subjects.*', 'teachers.name as teacher_name', 'teachers.phone as teacher_phone')
            ->orderBy('subjects.name')
            ->get();

        return view('livewire.pages.backend.student-my-subjects', compact('subjects'))
            ->layout('layouts.app');
    }
}
