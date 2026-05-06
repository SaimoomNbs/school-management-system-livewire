<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminResultReport extends Component
{
    public string $class_id = '';
    public string $exam_group_id = '';

    public function updatedClassId()
    {
        $this->exam_group_id = '';
    }

    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();

        $examGroups = $this->class_id
            ? DB::table('exam_groups')->where('class_id', $this->class_id)->orderByDesc('start_date')->get()
            : collect();

        $reportData = null;

        if ($this->exam_group_id) {
            // 1. Fetch Exams / Subjects
            $exams = DB::table('exams')
                ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
                ->where('exams.exam_group_id', $this->exam_group_id)
                ->select('exams.*', 'subjects.name as subject_name')
                ->orderBy('subjects.name')
                ->get();
            
            // 2. Fetch Results
            $examIds = $exams->pluck('id')->toArray();
            
            $results = DB::table('results')
                ->whereIn('exam_id', $examIds)
                ->get();
                
            $resultMatrix = [];
            foreach ($results as $res) {
                if (!isset($resultMatrix[$res->student_id])) {
                    $resultMatrix[$res->student_id] = [];
                }
                $resultMatrix[$res->student_id][$res->exam_id] = $res;
            }

            // 3. Fetch Enrolled Students for the class associated with this group
            $students = DB::table('students')
                ->where('class_id', $this->class_id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();

            // 4. Calculate Aggregates
            $processedStudents = [];
            $stats = [
                'total_passed' => 0,
                'total_failed' => 0,
                'avg_percentage' => 0,
            ];
            
            $sumPercentages = 0;
            $studentsEvaluated = 0;

            foreach ($students as $stu) {
                $studentData = [
                    'id'    => $stu->id,
                    'name'  => $stu->name,
                    'code'  => $stu->student_id,
                    'is_failed' => false,
                    'total_marks_obtained' => 0,
                    'max_marks' => 0,
                    'subject_scores' => []
                ];

                $hasAttemptedAny = false;

                foreach ($exams as $ex) {
                    $res = $resultMatrix[$stu->id][$ex->id] ?? null;
                    $obtained = $res ? (float) $res->marks : 0;
                    
                    $studentData['max_marks'] += $ex->total_marks;
                    
                    if ($res) {
                        $hasAttemptedAny = true;
                        $studentData['total_marks_obtained'] += $obtained;
                        
                        // Fail check based on pass_marks boundary
                        if ($obtained < $ex->pass_marks) {
                            $studentData['is_failed'] = true;
                        }
                    } else {
                        // Missing result implicitly means failure/absent for report
                        $studentData['is_failed'] = true;
                    }
                    
                    $studentData['subject_scores'][$ex->id] = $res;
                }

                if ($hasAttemptedAny) {
                    $studentsEvaluated++;
                    if ($studentData['is_failed']) {
                        $stats['total_failed']++;
                    } else {
                        $stats['total_passed']++;
                    }
                    if ($studentData['max_marks'] > 0) {
                        $sumPercentages += ($studentData['total_marks_obtained'] / $studentData['max_marks']) * 100;
                    }
                }
                
                $studentData['percentage'] = $studentData['max_marks'] > 0 
                    ? ($studentData['total_marks_obtained'] / $studentData['max_marks']) * 100 
                    : 0;

                $processedStudents[] = $studentData;
            }
            
            $stats['avg_percentage'] = $studentsEvaluated > 0 ? ($sumPercentages / $studentsEvaluated) : 0;

            // Sort by percentage descending
            usort($processedStudents, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

            $reportData = [
                'exams'    => $exams,
                'students' => $processedStudents,
                'stats'    => $stats,
            ];
        }

        return view('livewire.pages.backend.admin-result-report', compact('allClasses', 'examGroups', 'reportData'))
            ->layout('layouts.app');
    }
}
