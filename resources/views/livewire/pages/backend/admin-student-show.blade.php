<div x-data="{ activeTab: 'profile' }">

    {{-- ── Back nav ── --}}
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.students.index') }}" wire:navigate
            style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:500;color:var(--color-text-3);transition:color .14s ease;"
            onmouseover="this.style.color='var(--color-text-1)'" onmouseout="this.style.color='var(--color-text-3)'">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Students
        </a>
    </div>

    {{-- ── Profile header ── --}}
    <div class="card" style="padding:28px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:22px;flex-wrap:wrap;">
            <div class="profile-avatar-lg" style="background:linear-gradient(135deg,#10b981,#059669);">
                @if ($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="profile-avatar-img">
                @else
                    <span>{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px;">
                    <h1 class="page-title" style="margin:0;">{{ $student->name }}</h1>
                    @if ($student->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:14px;">
                    <span style="font-size:12.5px;color:var(--color-text-3);display:flex;align-items:center;gap:5px;">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                        <span style="font-family:monospace;font-weight:700;color:#10b981;">{{ $student->student_id }}</span>
                    </span>
                    @if ($student->class)
                        <span class="badge badge-accent">{{ $student->class->name }}{{ $student->section ? ' – ' . $student->section->name : '' }}</span>
                    @endif
                    @if ($student->phone)
                        <span style="font-size:12.5px;color:var(--color-text-3);">📱 {{ $student->phone }}</span>
                    @endif
                    @if ($student->email)
                        <span style="font-size:12.5px;color:var(--color-text-3);">✉ {{ $student->email }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.students.edit', $student) }}" wire:navigate class="btn-primary" id="btn-edit-student">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- ── Stat pills ── --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
        @php
            $totalFees   = $student->fees->sum('amount');
            $paidFees    = $student->fees->whereIn('status', ['Paid', 'paid'])->sum('amount');
            $pendingFees = $student->fees->whereIn('status', ['Unpaid', 'unpaid', 'Partial', 'partial'])->count();
            $present     = $student->attendance->where('status', 'present')->count();
            $absent      = $student->attendance->where('status', 'absent')->count();
            $attTotal    = $student->attendance->count();
            $attPct      = $attTotal > 0 ? round(($present / $attTotal) * 100) : 0;
        @endphp
        <div class="stat-card" style="text-align:center;padding:16px 12px;">
            <p style="font-size:22px;font-weight:750;color:var(--color-accent);">{{ $student->results->count() }}</p>
            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">Exams Taken</p>
        </div>
        <div class="stat-card" style="text-align:center;padding:16px 12px;">
            <p style="font-size:22px;font-weight:750;color:#10b981;">{{ $attPct }}%</p>
            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">Attendance</p>
        </div>
        <div class="stat-card" style="text-align:center;padding:16px 12px;">
            <p style="font-size:22px;font-weight:750;color:var(--color-text-1);">৳ {{ number_format($paidFees, 0) }}</p>
            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">Fees Paid</p>
        </div>
        <div class="stat-card" style="text-align:center;padding:16px 12px;">
            <p style="font-size:22px;font-weight:750;color:{{ $pendingFees > 0 ? 'var(--color-danger)' : 'var(--color-text-1)' }};">{{ $pendingFees }}</p>
            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">Pending Fees</p>
        </div>
    </div>

    {{-- ── Tab nav ── --}}
    <div class="tab-nav" style="margin-bottom:16px;">
        @foreach ([['profile','Profile','M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],['attendance','Attendance','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],['fees','Fees','M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],['results','Results','M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z']] as [$key, $label, $icon])
            <button type="button" @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}' ? 'tab-btn is-active' : 'tab-btn'"
                class="tab-btn">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ══════════════════════ PROFILE TAB ══════════════════════ --}}
    <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="card" style="padding:22px;">
                <div class="form-section-title" style="margin-bottom:16px;">Student Details</div>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach ([
                        ['DOB',          $student->dob?->format('d F Y'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Admission',    $student->admission_date?->format('d F Y'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Monthly Fee',  $student->monthly_fee > 0 ? '৳ '.number_format($student->monthly_fee,0) : '—', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['Email',        $student->email, 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ] as [$label, $value, $icon])
                        <div style="display:flex;gap:12px;align-items:center;">
                            <div style="width:30px;height:30px;border-radius:var(--r-sm);background:var(--color-surface-2);border:1px solid var(--color-border-lgt);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="color:var(--color-text-3)"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                            </div>
                            <div>
                                <p style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-4);">{{ $label }}</p>
                                <p style="font-size:13px;color:var(--color-text-1);margin-top:1px;">{{ $value ?? '—' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card" style="padding:22px;">
                <div class="form-section-title" style="margin-bottom:16px;">Guardian Information</div>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div><p style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-4);">Name</p><p style="font-size:13.5px;font-weight:600;color:var(--color-text-1);margin-top:2px;">{{ $student->guardian_name ?? '—' }}</p></div>
                    <div><p style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-4);">Phone</p><p style="font-size:13px;color:var(--color-text-1);margin-top:2px;">{{ $student->guardian_phone ?? '—' }}</p></div>
                    <div><p style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-4);">Address</p><p style="font-size:13px;color:var(--color-text-1);margin-top:2px;line-height:1.5;">{{ $student->guardian_address ?? '—' }}</p></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════ ATTENDANCE TAB ════════════════════ --}}
    <div x-show="activeTab === 'attendance'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="card" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div class="form-section-title" style="margin:0;">Recent Attendance (last 30 records)</div>
                <div style="display:flex;gap:8px;">
                    <span class="badge badge-success">Present: {{ $present }}</span>
                    <span class="badge badge-danger">Absent: {{ $absent }}</span>
                </div>
            </div>
            @if ($student->attendance->isEmpty())
                <div class="tbl-empty" style="padding:28px 0;"><p>No attendance records yet.</p></div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Date</th><th>Day</th><th>Status</th><th>Note</th></tr></thead>
                        <tbody>
                            @foreach ($student->attendance as $att)
                                <tr wire:key="att-{{ $att->id }}">
                                    <td style="font-weight:500;">{{ $att->date?->format('d M Y') }}</td>
                                    <td class="tbl-muted">{{ $att->date?->format('l') }}</td>
                                    <td>
                                        @php $attColors = ['present'=>'badge-success','absent'=>'badge-danger','late'=>'badge-warning','excused'=>'badge-accent']; @endphp
                                        <span class="badge {{ $attColors[$att->status] ?? 'badge-neutral' }}">{{ ucfirst($att->status) }}</span>
                                    </td>
                                    <td class="tbl-muted">{{ $att->note ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════ FEES TAB ══════════════════════ --}}
    <div x-show="activeTab === 'fees'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="card" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div class="form-section-title" style="margin:0;">Fee Records</div>
                <div style="display:flex;gap:8px;">
                    <span class="badge badge-success">Paid: ৳{{ number_format($paidFees, 0) }}</span>
                    <span class="badge badge-neutral">Total: ৳{{ number_format($totalFees, 0) }}</span>
                </div>
            </div>
            @if ($student->fees->isEmpty())
                <div class="tbl-empty" style="padding:28px 0;"><p>No fee records yet.</p></div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Type</th><th>Label</th><th>Month/Year</th><th>Amount</th><th>Due Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach ($student->fees->sortByDesc('created_at') as $fee)
                                <tr wire:key="fee-{{ $fee->id }}">
                                    <td><span class="badge badge-neutral">{{ ucfirst($fee->type) }}</span></td>
                                    <td class="tbl-muted">{{ $fee->label ?? '—' }}</td>
                                    <td class="tbl-muted">{{ $fee->month ? \Carbon\Carbon::create()->month($fee->month)->format('F') . ' ' . $fee->year : '—' }}</td>
                                    <td style="font-weight:600;">৳{{ number_format($fee->amount, 0) }}</td>
                                    <td class="tbl-muted">{{ $fee->due_date?->format('d M Y') ?? '—' }}</td>
                                    <td>
                                        @php $feeColors = ['paid'=>'badge-success','unpaid'=>'badge-danger','partial'=>'badge-warning']; @endphp
                                        <span class="badge {{ $feeColors[$fee->status] ?? 'badge-neutral' }}">{{ ucfirst($fee->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ════════════════════ RESULTS TAB ════════════════════ --}}
    <div x-show="activeTab === 'results'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="card" style="padding:22px;">
            <div class="form-section-title" style="margin-bottom:16px;">Exam Results</div>
            @if ($student->results->isEmpty())
                <div class="tbl-empty" style="padding:28px 0;"><p>No exam results yet.</p></div>
            @else
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Exam Group</th><th>Subject</th><th>Exam</th><th>Date</th><th>Marks</th><th>Total</th><th>Grade</th><th>Remarks</th></tr></thead>
                        <tbody>
                            @foreach ($student->results->sortByDesc(fn($r) => $r->exam?->exam_date) as $result)
                                <tr wire:key="result-{{ $result->id }}">
                                    <td class="tbl-muted">{{ $result->exam?->examGroup?->title ?? '—' }}</td>
                                    <td style="font-weight:500;">{{ $result->exam?->subject?->name ?? '—' }}</td>
                                    <td>{{ $result->exam?->title ?? '—' }}</td>
                                    <td class="tbl-muted">{{ $result->exam?->exam_date?->format('d M Y') ?? '—' }}</td>
                                    <td style="font-weight:700;color:var(--color-accent);">{{ $result->marks ?? '—' }}</td>
                                    <td class="tbl-muted">{{ $result->exam?->total_marks ?? '—' }}</td>
                                    <td>
                                        @if ($result->grade)
                                            <span class="badge badge-accent">{{ $result->grade }}</span>
                                        @else
                                            <span class="tbl-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="tbl-muted">{{ $result->remarks ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
