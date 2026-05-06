<?php

namespace App\Livewire\Backend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class AdminPaymentRecord extends Component
{
    public string $searchInvoice = '';
    public ?object $selectedInvoice = null;
    
    public string $amount = '';
    public string $payment_method = 'cash';
    public string $transaction_note = '';
    public string $paid_at = '';

    public function mount()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);
        $this->paid_at = now()->format('Y-m-d\TH:i');
    }

    public function selectInvoice(int $id)
    {
        $this->selectedInvoice = DB::table('invoices')
            ->join('students', 'invoices.student_id', '=', 'students.id')
            ->select('invoices.*', 'students.name as student_name', 'students.student_id as student_code')
            ->where('invoices.id', $id)
            ->first();

        if ($this->selectedInvoice) {
            $this->searchInvoice = $this->selectedInvoice->invoice_no;
            $this->amount = (string) $this->selectedInvoice->due_amount;
        }
    }

    public function updatedSearchInvoice()
    {
        $this->selectedInvoice = null;
        $this->amount = '';
    }

    public function recordPayment()
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        $this->validate([
            'amount' => 'required|numeric|min:1|max:' . ($this->selectedInvoice->due_amount ?? 0),
            'payment_method' => 'required|in:cash,bkash,bank,other',
            'paid_at' => 'required|date_format:Y-m-d\TH:i',
            'transaction_note' => 'nullable|string|max:500',
        ]);

        if (!$this->selectedInvoice) {
            session()->flash('error', 'Please select a valid invoice.');
            return;
        }

        DB::transaction(function () {
            $paymentAmount = (float) $this->amount;
            $newPaidAmt = $this->selectedInvoice->paid_amount + $paymentAmount;
            $newDueAmt  = $this->selectedInvoice->total_amount - $newPaidAmt;
            $newStatus  = $newDueAmt <= 0 ? 'paid' : 'partial';

            // 1. Update Invoice
            DB::table('invoices')->where('id', $this->selectedInvoice->id)->update([
                'paid_amount' => $newPaidAmt,
                'due_amount'  => $newDueAmt,
                'status'      => $newStatus,
                'updated_at'  => now(),
            ]);

            // 2. Insert Payment Record
            DB::table('payments')->insert([
                'student_id'       => $this->selectedInvoice->student_id,
                'invoice_id'       => $this->selectedInvoice->id,
                'fee_id'           => null, // General payment against invoice
                'amount'           => $paymentAmount,
                'payment_method'   => $this->payment_method,
                'transaction_note' => $this->transaction_note,
                'paid_at'          => $this->paid_at,
                'collected_by'     => Auth::id(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // 3. Keep Fees table in sync: mark as paid if invoice is fully paid
            // (If partial, fees logic requires distribution, keeping it simple here)
            if ($newStatus === 'paid') {
                $feeIds = DB::table('invoice_items')
                    ->where('invoice_id', $this->selectedInvoice->id)
                    ->pluck('fee_id');

                DB::table('fees')->whereIn('id', $feeIds)->update([
                    'status' => 'paid',
                    'updated_at' => now(),
                ]);
            }

            // 4. Record Transaction (Accounting)
            DB::table('transactions')->insert([
                'type'             => 'income',
                'amount'           => $paymentAmount,
                'transaction_date' => Carbon::parse($this->paid_at)->format('Y-m-d'),
                'note'             => "Payment for Invoice {$this->selectedInvoice->invoice_no} via {$this->payment_method}",
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
            
            session()->flash('success', "Payment of ৳{$paymentAmount} recorded successfully!");
        });

        $this->redirect(route('admin.fees.index'), navigate: true);
    }

    public function render()
    {
        $invoices = [];
        if (strlen($this->searchInvoice) >= 3 && !$this->selectedInvoice) {
            $invoices = DB::table('invoices')
                ->join('students', 'invoices.student_id', '=', 'students.id')
                ->select('invoices.*', 'students.name as student_name')
                ->whereIn('invoices.status', ['unpaid', 'partial'])
                ->where(function($q) {
                    $q->where('invoices.invoice_no', 'like', '%' . $this->searchInvoice . '%')
                      ->orWhere('students.name', 'like', '%' . $this->searchInvoice . '%')
                      ->orWhere('students.student_id', 'like', '%' . $this->searchInvoice . '%')
                      ->orWhere('students.phone', 'like', '%' . $this->searchInvoice . '%');
                })
                ->take(5)
                ->get();
        }

        return view('livewire.pages.backend.admin-payment-record', compact('invoices'))
            ->layout('layouts.app');
    }
}