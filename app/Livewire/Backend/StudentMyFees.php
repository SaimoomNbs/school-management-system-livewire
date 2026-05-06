<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentMyFees extends Component
{
    public function render()
    {
        $student = auth()->user()?->student;
        abort_unless($student, 403);

        $fees = DB::table('fees')
            ->where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->get();

        $totalPaid = $fees->whereIn('status', ['Paid', 'paid'])->sum('amount');
        $totalUnpaid = $fees->whereIn('status', ['Unpaid', 'unpaid', 'Partial', 'partial'])->sum('amount');

        $payments = DB::table('payments')
            ->where('student_id', $student->id)
            ->orderByDesc('paid_at')
            ->get();

        return view('livewire.pages.backend.student-my-fees', compact('fees', 'totalPaid', 'totalUnpaid', 'payments'))
            ->layout('layouts.app');
    }
}
