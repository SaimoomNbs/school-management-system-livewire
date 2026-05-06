<div>
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Attendance Report</h1>
            <p class="page-desc">View daily attendance records with filters and summary stats.</p>
        </div>
    </div>

    {{-- ── Summary stat cards ── --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;">
        @foreach ([
            ['Total',   $summary['total'],   '#5b5ef4', 'var(--color-accent-dim)',  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['Present', $summary['present'], '#16a34a', '#f0fdf4',                  'M5 13l4 4L19 7'],
            ['Absent',  $summary['absent'],  '#dc2626', '#fff5f5',                  'M6 18L18 6M6 6l12 12'],
            ['Late',    $summary['late'],    '#d97706', '#fffbeb',                  'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Excused', $summary['excused'], '#7c3aed', '#f5f3ff',                  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as [$label, $count, $color, $bg, $icon])
            <div class="stat-card" style="padding:16px;text-align:center;border-top:3px solid {{ $color }};">
                <div style="width:36px;height:36px;border-radius:var(--r-full);background:{{ $bg }};display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="{{ $color }}" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                </div>
                <p style="font-size:24px;font-weight:800;color:{{ $color }};letter-spacing:-.5px;">{{ number_format($count) }}</p>
                <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- ── Filter card ── --}}
    <div class="card" style="padding:16px 18px;margin-bottom:16px;">
        <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">

            {{-- Class --}}
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:11.5px;font-weight:600;color:var(--color-text-3);">Class</label>
                <select wire:model.live="filterClassId" class="tbl-filter-select" id="report-filter-class">
                    <option value="">All Classes</option>
                    @foreach ($allClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Section --}}
            @if ($filterClassId)
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <label style="font-size:11.5px;font-weight:600;color:var(--color-text-3);">Section</label>
                    <select wire:model.live="filterSectionId" class="tbl-filter-select" id="report-filter-section">
                        <option value="">All Sections</option>
                        @foreach ($allSections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Student --}}
                <div style="display:flex;flex-direction:column;gap:5px;">
                    <label style="font-size:11.5px;font-weight:600;color:var(--color-text-3);">Student</label>
                    <select wire:model.live="filterStudentId" class="tbl-filter-select" id="report-filter-student">
                        <option value="">All Students</option>
                        @foreach ($allStudents as $stu)
                            <option value="{{ $stu->id }}">{{ $stu->name }} ({{ $stu->student_id }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Month --}}
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:11.5px;font-weight:600;color:var(--color-text-3);">Month</label>
                <select wire:model.live="filterMonth" class="tbl-filter-select" id="report-filter-month">
                    <option value="">All Months</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Year --}}
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label style="font-size:11.5px;font-weight:600;color:var(--color-text-3);">Year</label>
                <select wire:model.live="filterYear" class="tbl-filter-select" id="report-filter-year">
                    <option value="">All Years</option>
                    @foreach ($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <span class="tbl-count" style="margin-left:auto;">{{ $summary['total'] }} records</span>
        </div>
    </div>

    {{-- ── Records table ── --}}
    <div class="card">
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:52px"></th>
                        <th>Student</th>
                        <th>Class / Section</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Marked By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr wire:key="rec-{{ $record->id }}">
                            <td style="padding-right:0;">
                                <div class="teacher-avatar" style="width:34px;height:34px;font-size:10px;background:linear-gradient(135deg,#10b981,#059669);">
                                    @if ($record->student_photo)
                                        <img src="{{ asset('storage/' . $record->student_photo) }}" class="teacher-avatar-img" alt="">
                                    @else
                                        {{ strtoupper(substr($record->student_name, 0, 2)) }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:600;color:var(--color-text-1);font-size:13px;">{{ $record->student_name }}</span>
                                <div style="font-size:11px;color:var(--color-text-4);font-family:monospace;margin-top:1px;">{{ $record->student_code }}</div>
                            </td>
                            <td>
                                <span class="badge badge-accent" style="margin-bottom:2px;">{{ $record->class_name }}</span>
                                @if ($record->section_name)
                                    <div style="font-size:11.5px;color:var(--color-text-3);">{{ $record->section_name }}</div>
                                @endif
                            </td>
                            <td style="font-weight:500;white-space:nowrap;">{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($record->date)->format('D') }}</td>
                            <td>
                                @php $sc = ['present'=>'badge-success','absent'=>'badge-danger','late'=>'badge-warning','excused'=>'badge-accent']; @endphp
                                <span class="badge {{ $sc[$record->status] ?? 'badge-neutral' }}">{{ ucfirst($record->status) }}</span>
                            </td>
                            <td class="tbl-muted" style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $record->note }}">
                                {{ $record->note ?? '—' }}
                            </td>
                            <td class="tbl-muted">{{ $record->marker_name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="tbl-empty">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p>No attendance records found.</p>
                                <p style="font-size:12px;margin-top:4px;color:var(--color-text-4);">Try adjusting the filters above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($records->hasPages())
            <div class="mt-4">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
