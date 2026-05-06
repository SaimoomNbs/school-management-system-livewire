<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Student;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminGenerateMonthlyFees extends Component
{
    public string $class_id = '';
    public string $month    = '';
    public string $year     = '';
    public string $due_date = '';

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);
        $this->month    = now()->format('m');
        $this->year     = now()->format('Y');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
    }

    public function generate()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        $this->validate([
            'class_id' => $this->class_id === 'all' ? 'required' : 'required|exists:classes,id',
            'month'    => 'required|numeric|min:1|max:12',
            'year'     => 'required|numeric|min:2000|max:2100',
            'due_date' => 'required|date',
        ]);

        $query = Student::where('status', 1);
        if ($this->class_id !== 'all') {
            $query->where('class_id', $this->class_id);
        }
        
        $students = $query->get();

        if ($students->isEmpty()) {
            session()->flash('error', 'No active students found in the selected class(es).');
            return;
        }
        
        $monthName = Carbon::create()->month((int)$this->month)->format('F');
        $label     = "Monthly Fee - {$monthName} {$this->year}";
        $now       = now();

        $existingFeeStudentIds = DB::table('fees')
            ->where('type', 'monthly')
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->whereIn('student_id', $students->pluck('id'))
            ->pluck('student_id')
            ->toArray();
            
        $studentsToProcess = $students->whereNotIn('id', $existingFeeStudentIds);
        $skippedRows = $students->count() - $studentsToProcess->count();
        $insertedRows = 0;

        if ($studentsToProcess->isNotEmpty()) {
            $data = $studentsToProcess->map(fn ($s) => [
                'student_id' => $s->id,
                'type'       => 'monthly',
                'amount'     => $s->monthly_fee ?: 0,
                'month'      => $this->month,
                'year'       => $this->year,
                'label'      => $label,
                'due_date'   => $this->due_date,
                'status'     => 'unpaid',
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray();

            $insertedRows = DB::table('fees')->insertOrIgnore($data);

            if ($insertedRows > 0) {
                // Trigger Notifications
                foreach ($data as $feePayload) {
                    NotificationService::send(
                        'fee_due',
                        'Monthly Fee Due',
                        "Your monthly fee for {$monthName} {$this->year} of Tk {$feePayload['amount']} has been generated. Due by " . Carbon::parse($this->due_date)->format('d M'),
                        'App\Models\Student',
                        $feePayload['student_id']
                    );
                }
            }
        }

        if ($insertedRows > 0 || $skippedRows > 0) {
            session()->flash('success', "Generated {$insertedRows} fees. Skipped {$skippedRows} existing records.");
        } else {
            session()->flash('error', "No students available to generate fees.");
        }

        $this->redirect(route('admin.fees.index'), navigate: true);
    }

    public function render()
    {
        $allClasses = ClassModel::orderBy('name')->get();
        $years      = range(now()->year - 1, now()->year + 2);

        return view('livewire.pages.backend.admin-generate-monthly-fees', compact('allClasses', 'years'))
            ->layout('layouts.app');
    }
}