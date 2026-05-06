<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentMyResults extends Component
{
    public int $studentId;
    public int $classId;

    public function mount()
    {
        $student = auth()->user()?->student;
        abort_unless($student, 403);
        $this->studentId = $student->id;
        $this->classId = $student->class_id;
    }

    public function render()
    {
        // 1. Fetch relevant exam groups where the student has results OR exams
        $results = DB::table('exams')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->leftJoin('results', function($join) {
                $join->on('results.exam_id', '=', 'exams.id')
                     ->where('results.student_id', '=', $this->studentId);
            })
            ->where('exam_groups.class_id', $this->classId)
            ->select(
                'exams.id as exam_id',
                'exams.title as exam_title', 
                'exams.total_marks', 
                'exams.pass_marks', 
                'exams.exam_date',
                'exam_groups.id as group_id',
                'exam_groups.title as group_title',
                'subjects.name as subject_name',
                'results.marks',
                'results.grade',
                'results.remarks',
                'results.attachment'
            )
            ->orderBy('exam_groups.start_date', 'desc')
            ->orderBy('subjects.name')
            ->get();

        // 2. Group visually by Exam Group
        $reportCards = [];
        
        foreach ($results as $res) {
            $gid = $res->group_id;
            if (!isset($reportCards[$gid])) {
                $reportCards[$gid] = [
                    'group_title' => $res->group_title,
                    'exams'       => [],
                    'total_marks' => 0,
                    'obtained'    => 0,
                    'has_failed'  => false,
                    'all_published'=> true,
                ];
            }
            
            $reportCards[$gid]['exams'][] = $res;
            
            if ($res->marks !== null) {
                $reportCards[$gid]['total_marks'] += $res->total_marks;
                $reportCards[$gid]['obtained'] += $res->marks;
                
                if ($res->marks < $res->pass_marks || $res->grade === 'F') {
                    $reportCards[$gid]['has_failed'] = true;
                }
            } else {
                $reportCards[$gid]['all_published'] = false;
            }
        }

        return view('livewire.pages.backend.student-my-results', compact('reportCards'))
            ->layout('layouts.app');
    }
}