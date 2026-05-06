<div>
    {{-- ── Page header ── --}}
    <div class="page-header" style="flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 class="page-title">Fees & Payments</h1>
            <p class="page-desc">Track and manage student fees, filtering by class, student, or month.</p>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
            <a href="{{ route('admin.fees.generate-monthly') }}" wire:navigate class="btn-outline">
                Bulk Generate Monthly Fees
            </a>
            <a href="{{ route('admin.invoices.create') }}" wire:navigate class="btn-primary">
                Create Invoice
            </a>
            <a href="{{ route('admin.payments.create') }}" wire:navigate class="btn-primary">
                Record Payment
            </a>
        </div>
    </div>

    {{-- ── Filters & Card ── --}}
    <div class="card">
        <div class="tbl-toolbar" style="flex-wrap:wrap;gap:8px;">
            <div class="tbl-search-wrap" style="min-width:200px;flex:1;">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search fee label, student name...">
            </div>

            <select wire:model.live="filterClassId" class="tbl-filter-select">
                <option value="">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>

            @if ($filterClassId)
                <select wire:model.live="filterStudentId" class="tbl-filter-select">
                    <option value="">All Students</option>
                    @foreach ($allStudents as $stu)
                        <option value="{{ $stu->id }}">{{ $stu->name }}</option>
                    @endforeach
                </select>
            @endif

            <select wire:model.live="filterMonth" class="tbl-filter-select">
                <option value="">All Months</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create()->month($m)->format('M') }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterYear" class="tbl-filter-select">
                <option value="">All Years</option>
                @foreach ($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            
            <select wire:model.live="filterStatus" class="tbl-filter-select">
                <option value="">All Statuses</option>
                <option value="paid">Paid</option>
                <option value="partial">Partial</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>

        <div style="padding:10px 18px;background:var(--color-surface-2);border-top:1px solid var(--color-border);border-bottom:1px solid var(--color-border);display:flex;justify-content:flex-end;">
            <div style="font-weight:600;font-size:13px;color:var(--color-text-2);">
                Total Assessed: <span style="color:var(--color-text-1);">৳{{ number_format($totalAmount, 0) }}</span>
            </div>
        </div>

        <div class="tbl-wrap" style="overflow-x: auto;">
            <table class="tbl" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th style="width:52px"></th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Fee Label</th>
                        <th>Period</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr wire:key="fee-{{ $fee->id }}">
                            <td style="padding-right:0;">
                                <div class="teacher-avatar" style="width:34px;height:34px;font-size:10px;">
                                    @if ($fee->student_photo)
                                        <img src="{{ asset('storage/' . $fee->student_photo) }}" class="teacher-avatar-img">
                                    @else
                                        {{ strtoupper(substr($fee->student_name, 0, 2)) }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:600;color:var(--color-text-1);font-size:13px;">{{ $fee->student_name }}</span>
                                <div style="font-size:11px;color:var(--color-text-4);font-family:monospace;">{{ $fee->student_code }}</div>
                            </td>
                            <td><span class="badge badge-accent">{{ $fee->class_name ?? '—' }}</span></td>
                            <td style="font-weight:500;">{{ $fee->label }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::create()->month((int) $fee->month)->format('M') }} {{ $fee->year }}</td>
                            <td class="tbl-muted">{{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('d M Y') : '—' }}</td>
                            <td style="font-weight:700;color:var(--color-text-1);">৳{{ number_format($fee->amount, 0) }}</td>
                            <td>
                                @php $sc = ['paid'=>'badge-success','unpaid'=>'badge-danger','partial'=>'badge-warning']; @endphp
                                <span class="badge {{ $sc[strtolower($fee->status)] ?? 'badge-neutral' }}">{{ ucfirst($fee->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="tbl-empty" style="padding:40px;">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p>No fee records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($fees->hasPages())
            <div class="mt-4">{{ $fees->links() }}</div>
        @endif
    </div>
</div>