<div>
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.fees.index') }}" wire:navigate class="btn-outline" style="padding:7px 10px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-title">Record Payment</h1>
                <p class="page-desc">Process payments against active student invoices.</p>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:20px;align-items:flex-start;">
        
        {{-- Left: Invoice Selection --}}
        <div class="card" style="padding:20px;flex:1;min-width:300px;max-width:100%;">
            <div class="form-section-title">Lookup Invoice</div>
            
            <div class="form-group" style="position:relative;margin-bottom:0;">
                <input type="text" wire:model.live.debounce.300ms="searchInvoice" class="form-input" placeholder="Invoice No, Student Name...">
                
                @if (strlen($searchInvoice) >= 3 && !$selectedInvoice)
                    <div style="position:absolute;top:100%;left:0;right:0;background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--r-md);box-shadow:var(--shadow-md);margin-top:6px;z-index:50;">
                        @forelse ($invoices as $inv)
                            <div wire:click="selectInvoice({{ $inv->id }})" style="padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--color-border-lgt);display:flex;flex-direction:column;gap:2px;transition:background 0.15s ease;">
                                <div style="display:flex;justify-content:space-between;">
                                    <span style="font-family:monospace;font-weight:700;color:var(--color-accent);font-size:12px;">{{ $inv->invoice_no }}</span>
                                    <span style="font-weight:700;color:var(--color-text-1);font-size:13px;">৳{{ number_format($inv->due_amount, 0) }}</span>
                                </div>
                                <div style="font-size:11.5px;color:var(--color-text-3);">{{ $inv->student_name }}</div>
                            </div>
                        @empty
                            <div style="padding:12px 14px;font-size:12px;color:var(--color-text-3);text-align:center;">No unpaid invoices found</div>
                        @endforelse
                    </div>
                @endif
            </div>

            @if ($selectedInvoice)
                <div style="margin-top:16px;padding:12px;background:var(--color-surface-2);border-radius:var(--r-md);border:1px solid var(--color-border-lgt);">
                    <div style="font-size:11px;font-weight:700;color:var(--color-text-4);margin-bottom:6px;text-transform:uppercase;">Invoice Info</div>
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:var(--color-accent);">{{ $selectedInvoice->invoice_no }}</div>
                        <div style="font-size:12.5px;color:var(--color-text-1);font-weight:500;">{{ $selectedInvoice->student_name }} ({{ $selectedInvoice->student_code }})</div>
                        <div style="font-size:12.5px;color:var(--color-text-3);margin-top:4px;">Total Amount: ৳{{ number_format($selectedInvoice->total_amount, 0) }}</div>
                        <div style="font-size:12.5px;color:var(--color-text-3);">Paid Amount: ৳{{ number_format($selectedInvoice->paid_amount, 0) }}</div>
                        <div style="font-size:13.5px;font-weight:700;color:var(--color-danger);margin-top:4px;">Due Today: ৳{{ number_format($selectedInvoice->due_amount, 0) }}</div>
                    </div>
                    <button wire:click="updatedSearchInvoice" style="margin-top:10px;font-size:12px;color:var(--color-danger);background:none;border:none;cursor:pointer;text-decoration:underline;">Change Invoice</button>
                </div>
            @endif
        </div>

        {{-- Right: Payment Form --}}
        <div class="card" style="padding:24px;flex:2;min-width:300px;max-width:100%;">
            <div class="form-section-title">Payment Details</div>

            @if (!$selectedInvoice)
                <div class="tbl-empty" style="padding:48px;">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p>Search and select an invoice to record a payment.</p>
                </div>
            @else
                <form wire:submit="recordPayment">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Payment Amount (৳) <span class="form-required">*</span></label>
                            <input type="number" step="0.01" wire:model="amount" class="form-input @error('amount') is-invalid @enderror" placeholder="Amount...">
                            @error('amount') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Date <span class="form-required">*</span></label>
                            <input type="datetime-local" wire:model="paid_at" class="form-input @error('paid_at') is-invalid @enderror">
                            @error('paid_at') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="form-required">*</span></label>
                        <select wire:model="payment_method" class="form-input @error('payment_method') is-invalid @enderror">
                            <option value="cash">Cash</option>
                            <option value="bkash">bKash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="other">Other</option>
                        </select>
                        @error('payment_method') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:24px;">
                        <label class="form-label">Transaction Note <span class="form-optional">(optional)</span></label>
                        <input type="text" wire:model="transaction_note" class="form-input @error('transaction_note') is-invalid @enderror" placeholder="Reference ID, check number...">
                        @error('transaction_note') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:12px;border-top:1px solid var(--color-border-lgt);padding-top:18px;">
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="recordPayment">Confirm Payment</span>
                            <span wire:loading wire:target="recordPayment">Processing...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>