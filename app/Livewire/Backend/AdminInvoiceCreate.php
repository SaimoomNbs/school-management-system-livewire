<?php

namespace App\Livewire\Backend;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminInvoiceCreate extends Component
{
    public string $searchStudent = '';
    public ?int $selectedStudentId = null;
    public $unpaidFees = [];
    public array $selectedFeeIds = [];

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);
    }

    public function updatedSearchStudent()
    {
        $this->selectedStudentId = null;
        $this->unpaidFees = [];
        $this->selectedFeeIds = [];
    }

    public function selectStudent(int $id)
    {
        $this->selectedStudentId = $id;
        $this->searchStudent = Student::find($id)?->name ?? '';
        
        $this->loadUnpaidFees();
    }

    public function loadUnpaidFees()
    {
        $this->unpaidFees = DB::table('fees')
            ->where('student_id', $this->selectedStudentId)
            ->whereIn('status', ['unpaid', 'partial'])
            // Don't include fees already bound to an unpaid/partial invoice
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('invoice_items')
                      ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                      ->whereColumn('invoice_items.fee_id', 'fees.id')
                      ->whereIn('invoices.status', ['unpaid', 'partial']);
            })
            ->get();
            
        $this->selectedFeeIds = [];
    }

    public function toggleFee($feeId)
    {
        if (in_array((int)$feeId, $this->selectedFeeIds)) {
            $this->selectedFeeIds = array_diff($this->selectedFeeIds, [(int)$feeId]);
        } else {
            $this->selectedFeeIds[] = (int)$feeId;
        }
    }

    public function createInvoice()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        if (empty($this->selectedFeeIds) || !$this->selectedStudentId) {
            session()->flash('error', 'Please select a student and at least one fee.');
            return;
        }

        $feesToInvoice = collect($this->unpaidFees)->whereIn('id', $this->selectedFeeIds);
        $totalAmount = $feesToInvoice->sum('amount'); // In a complex setup, handle 'partial' amounts remaining

        DB::transaction(function () use ($feesToInvoice, $totalAmount) {
            $nextId    = (DB::table('invoices')->max('id') ?? 0) + 1;
            $invoiceNo = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $invoiceId = DB::table('invoices')->insertGetId([
                'invoice_no'   => $invoiceNo,
                'student_id'   => $this->selectedStudentId,
                'total_amount' => $totalAmount,
                'paid_amount'  => 0,
                'due_amount'   => $totalAmount,
                'status'       => 'unpaid',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $invoiceItems = $feesToInvoice->map(fn($fee) => [
                'invoice_id' => $invoiceId,
                'fee_id'     => $fee->id,
                'amount'     => $fee->amount,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            DB::table('invoice_items')->insert($invoiceItems);

            session()->flash('success', "Invoice {$invoiceNo} created successfully!");
        });

        $this->redirect(route('admin.payments.create'), navigate: true);
    }

    public function render()
    {
        $students = [];
        if (strlen($this->searchStudent) >= 2 && !$this->selectedStudentId) {
            $students = Student::where(function($q) {
                $q->where('name', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('student_id', 'like', '%' . $this->searchStudent . '%')
                  ->orWhere('phone', 'like', '%' . $this->searchStudent . '%');
            })
            ->take(10)
            ->get();
        }

        return view('livewire.pages.backend.admin-invoice-create', compact('students'))
            ->layout('layouts.app');
    }
}