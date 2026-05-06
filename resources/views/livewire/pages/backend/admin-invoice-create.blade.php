<div>
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.fees.index') }}" wire:navigate class="btn-outline" style="padding:7px 10px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-title">Create Invoice</h1>
                <p class="page-desc">Consolidate multiple unpaid fees into a single invoice for a student.</p>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:20px;align-items:flex-start;">
        
        {{-- Left: Student Selection --}}
        <div class="card" style="padding:20px;flex:1;min-width:300px;max-width:100%;">
            <div class="form-section-title">Lookup Student</div>
            
            <div class="form-group" style="position:relative;margin-bottom:0;">
                <input type="text" wire:model.live.debounce.300ms="searchStudent" class="form-input" placeholder="Name, ID, or Phone...">
                
                @if (strlen($searchStudent) >= 2 && !$selectedStudentId)
                    <div style="position:absolute;top:100%;left:0;right:0;background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--r-md);box-shadow:var(--shadow-md);margin-top:6px;z-index:50;">
                        @forelse ($students as $stu)
                            <div wire:click="selectStudent({{ $stu->id }})" style="padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--color-border-lgt);display:flex;align-items:center;gap:12px;transition:background 0.15s ease;">
                                <div style="font-size:13px;font-weight:600;color:var(--color-text-1);">{{ $stu->name }}</div>
                                <div style="font-size:11px;color:var(--color-text-4);font-family:monospace;margin-left:auto;">{{ $stu->student_id }}</div>
                            </div>
                        @empty
                            <div style="padding:12px 14px;font-size:12px;color:var(--color-text-3);text-align:center;">No students found</div>
                        @endforelse
                    </div>
                @endif
            </div>

            @if ($selectedStudentId)
                <div style="margin-top:16px;padding:12px;background:var(--color-surface-2);border-radius:var(--r-md);border:1px solid var(--color-border-lgt);">
                    <div style="font-size:11.5px;font-weight:700;color:var(--color-text-3);margin-bottom:4px;text-transform:uppercase;">Selected Student</div>
                    <div style="font-weight:700;font-size:14px;color:var(--color-text-1);">{{ $searchStudent }}</div>
                    <button wire:click="$set('searchStudent', ''); $set('selectedStudentId', null);" style="margin-top:8px;font-size:12px;color:var(--color-danger);background:none;border:none;cursor:pointer;text-decoration:underline;">Change Student</button>
                </div>
            @endif
        </div>

        {{-- Right: Unpaid Fees & Creation --}}
        <div class="card" style="padding:24px;flex:2;min-width:300px;max-width:100%;">
            <div class="form-section-title">Available Fees for Invoicing</div>

            @if (!$selectedStudentId)
                <div class="tbl-empty" style="padding:32px;">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p>Search and select a student to see unpaid fees.</p>
                </div>
            @elseif (empty($unpaidFees))
                <div class="tbl-empty" style="padding:32px;color:var(--color-success);">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p style="font-weight:600;">No outstanding fees.</p>
                    <p style="font-size:12.5px;color:var(--color-text-3);margin-top:4px;">All fees are either paid or already invoiced.</p>
                </div>
            @else
                <div class="form-info-box" style="margin-bottom:16px;">
                    Select the fees you wish to bundle into a single invoice.
                </div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach ($unpaidFees as $fee)
                        <label style="display:flex;align-items:center;padding:12px 16px;border:1.5px solid {{ in_array($fee->id, $selectedFeeIds) ? 'var(--color-accent)' : 'var(--color-border)' }};border-radius:var(--r-md);cursor:pointer;background:{{ in_array($fee->id, $selectedFeeIds) ? 'var(--color-accent-dim)' : 'var(--color-surface)' }};transition:all 0.2s ease;">
                            <input type="checkbox" wire:click="toggleFee({{ $fee->id }})" value="{{ $fee->id }}" style="margin-right:16px;accent-color:var(--color-accent);width:16px;height:16px;">
                            <div style="flex:1;">
                                <div style="font-weight:600;font-size:13.5px;color:var(--color-text-1);">{{ $fee->label }}</div>
                                <div style="font-size:12px;color:var(--color-text-3);margin-top:2px;">Due: {{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('d M Y') : 'N/A' }} </div>
                            </div>
                            <div style="font-weight:700;font-size:15px;color:var(--color-accent);">৳{{ number_format($fee->amount, 0) }}</div>
                        </label>
                    @endforeach
                </div>

                <div style="margin-top:24px;border-top:1px solid var(--color-border-lgt);padding-top:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;">
                    @php
                        $sum = collect($unpaidFees)->whereIn('id', $selectedFeeIds)->sum('amount');
                    @endphp
                    <div>
                        <div style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Invoice Total</div>
                        <div style="font-size:20px;font-weight:800;color:var(--color-text-1);">৳{{ number_format($sum, 0) }}</div>
                    </div>
                    <button wire:click="createInvoice" wire:loading.attr="disabled" class="btn-primary" @if(empty($selectedFeeIds)) disabled style="opacity:0.6;cursor:not-allowed;" @endif>
                        <span wire:loading.remove wire:target="createInvoice">Create Invoice</span>
                        <span wire:loading wire:target="createInvoice">Creating...</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>