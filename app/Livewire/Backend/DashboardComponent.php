<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardComponent extends Component
{
    public function render()
    {
        $user = auth()->user();
        if ($user && $user->hasRole('student')) {
            return $this->renderStudentDashboard($user->student);
        }

        if ($user && $user->hasRole('teacher')) {
            return $this->renderTeacherDashboard($user);
        }

        $stats = [
            'total_students' => DB::table('students')->count(),
            'total_teachers' => DB::table('teachers')->count(),
            'total_users'    => DB::table('users')->count(),
            'total_revenue'  => DB::table('payments')->sum('amount'),
            'total_events'   => DB::table('events')->count()
        ];

        // Fetch recent payments and recent students mimicking "activity log" purely dynamically mapping
        $recent_activity = [
            'payments' => DB::table('payments')
                ->join('students', 'payments.student_id', '=', 'students.id')
                ->select('payments.*', 'students.name as student_name')
                ->orderByDesc('paid_at')
                ->limit(5)
                ->get(),
            'students' => DB::table('students')
                ->orderByDesc('admission_date')
                ->limit(5)
                ->get()
        ];

        return view('livewire.pages.backend.dashboard', compact('stats', 'recent_activity'))
            ->layout('layouts.app');
    }

    private function renderStudentDashboard($student)
    {
        $stats = [
            'attendance_percentage' => 100,
            'due_fees' => 0,
            'class_info' => '—'
        ];

        if ($student) {
            $totalDays = DB::table('attendance')->where('student_id', $student->id)->count();
            if ($totalDays > 0) {
                $presentDays = DB::table('attendance')->where('student_id', $student->id)->where('status', 'Present')->count();
                $stats['attendance_percentage'] = round(($presentDays / $totalDays) * 100, 1);
            }

            $stats['due_fees'] = DB::table('fees')->where('student_id', $student->id)->whereIn('status', ['Unpaid', 'Partial'])->sum('amount');
            
            $classModel = DB::table('classes')->where('id', $student->class_id)->first();
            $sectionModel = DB::table('sections')->where('id', $student->section_id)->first();
            
            $stats['class_info'] = ($classModel->name ?? '') . ' (' . ($sectionModel->name ?? '') . ')';
        }

        $upcomingExams = DB::table('exams')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->where('exam_groups.class_id', $student->class_id ?? 0)
            ->where('exams.exam_date', '>=', now()->toDateString())
            ->orderBy('exams.exam_date', 'asc')
            ->select('exams.*', 'subjects.name as subject_name')
            ->limit(5)
            ->get();

        $notifications = DB::table('notifications')
            ->latest()
            ->limit(3)
            ->get();

        return view('livewire.pages.backend.student-dashboard-component', compact('stats', 'upcomingExams', 'notifications'))
            ->layout('layouts.app');
    }

    private function renderTeacherDashboard($user)
    {
        $stats = [
            'total_students' => DB::table('students')->count(),
            'total_classes'  => DB::table('classes')->count(),
            'total_subjects' => DB::table('subjects')->count(),
            'total_events'   => DB::table('events')->count()
        ];

        // Fetch recent students
        $recent_activity = [
            'students' => DB::table('students')
                ->orderByDesc('admission_date')
                ->limit(5)
                ->get()
        ];

        $upcomingExams = DB::table('exams')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->join('exam_groups', 'exams.exam_group_id', '=', 'exam_groups.id')
            ->where('exams.exam_date', '>=', now()->toDateString())
            ->orderBy('exams.exam_date', 'asc')
            ->select('exams.*', 'subjects.name as subject_name', 'exam_groups.title as group_name')
            ->limit(5)
            ->get();

        $notifications = DB::table('notifications')
            ->latest()
            ->limit(3)
            ->get();

        return view('livewire.pages.backend.teacher-dashboard-component', compact('stats', 'recent_activity', 'upcomingExams', 'notifications'))
            ->layout('layouts.app');
    }
}