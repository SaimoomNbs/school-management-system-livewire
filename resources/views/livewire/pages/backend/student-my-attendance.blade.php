<div>
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">My Attendance</h1>
            <p class="page-desc">Your personal attendance history by month.</p>
        </div>
    </div>

    {{-- ── Month navigation ── --}}
    <div class="card" style="padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;gap:16px;">
        <button wire:click="prevMonth" class="btn-outline" style="padding:7px 12px;" id="btn-prev-month">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <div style="flex:1;text-align:center;">
            <p style="font-size:18px;font-weight:700;color:var(--color-text-1);">
                {{ \Carbon\Carbon::create((int)$filterYear, (int)$filterMonth, 1)->format('F Y') }}
            </p>
        </div>

        <button wire:click="nextMonth"
            class="btn-outline"
            style="padding:7px 12px;{{ ($filterYear >= now()->year && $filterMonth >= now()->format('m')) ? 'opacity:.4;pointer-events:none;' : '' }}"
            id="btn-next-month">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Year/month fallback dropdowns --}}
        <select wire:model.live="filterYear" class="tbl-filter-select" style="width:90px;" id="my-att-year">
            @foreach ($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── Summary stat pills ── --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
        @foreach ([
            ['Present', $summary['present'], '#16a34a', '#f0fdf4', 'P'],
            ['Absent',  $summary['absent'],  '#dc2626', '#fff5f5', 'A'],
            ['Late',    $summary['late'],    '#d97706', '#fffbeb', 'L'],
            ['Excused', $summary['excused'], '#7c3aed', '#f5f3ff', 'E'],
        ] as [$lbl, $cnt, $col, $bg, $abbr])
            <div class="stat-card" style="padding:16px 12px;text-align:center;border-left:3px solid {{ $col }};">
                <div style="width:34px;height:34px;border-radius:var(--r-full);background:{{ $bg }};display:flex;align-items:center;justify-content:center;margin:0 auto 8px;font-size:13px;font-weight:800;color:{{ $col }};">{{ $abbr }}</div>
                <p style="font-size:26px;font-weight:800;color:{{ $col }};line-height:1;">{{ $cnt }}</p>
                <p style="font-size:12px;color:var(--color-text-3);margin-top:4px;">{{ $lbl }}</p>
            </div>
        @endforeach
    </div>

    {{-- ── Calendar grid ── --}}
    <div class="card" style="padding:22px;margin-bottom:16px;">
        <div class="form-section-title" style="margin-bottom:16px;">Month Calendar</div>

        <div class="att-calendar">
            {{-- Day headers --}}
            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                <div class="att-cal-header">{{ $day }}</div>
            @endforeach

            {{-- Empty cells before 1st --}}
            @for ($e = 1; $e < $firstDayOfWeek; $e++)
                <div class="att-cal-cell att-empty"></div>
            @endfor

            {{-- Day cells --}}
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateKey = sprintf('%d-%02d-%02d', $filterYear, $filterMonth, $d);
                    $record  = $byDay[$dateKey] ?? null;
                    $status  = $record?->status ?? null;
                    $isToday = $dateKey === now()->format('Y-m-d');
                @endphp
                <div class="att-cal-cell {{ $status ? 'att-'.$status : 'att-no-record' }} {{ $isToday ? 'is-today' : '' }}"
                    title="{{ $status ? ucfirst($status) : ($isToday ? 'Today' : 'No Record') }}">
                    <span class="att-cal-day">{{ $d }}</span>
                    @if ($status)
                        <span class="att-cal-status">{{ strtoupper(substr($status, 0, 1)) }}</span>
                    @endif
                </div>
            @endfor
        </div>

        {{-- Legend --}}
        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:16px;padding-top:14px;border-top:1px solid var(--color-border-lgt);">
            @foreach ([['present','#16a34a','Present'],['absent','#dc2626','Absent'],['late','#d97706','Late'],['excused','#7c3aed','Excused'],['no-record','var(--color-text-4)','No Record']] as [$cls, $col, $lbl])
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--color-text-3);">
                    <div class="att-cal-cell att-{{ $cls }}" style="width:20px;height:20px;font-size:9px;border-radius:4px;flex-shrink:0;"></div>
                    {{ $lbl }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Monthly table view ── --}}
    <div class="card">
        <div style="padding:18px 20px 14px;border-bottom:1px solid var(--color-border-lgt);">
            <div class="form-section-title" style="margin:0;">Daily Records</div>
        </div>

        @if ($records->isEmpty())
            <div class="tbl-empty" style="padding:40px;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p>No attendance records for this month.</p>
            </div>
        @else
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $rec)
                            <tr wire:key="myrec-{{ $rec->id }}">
                                <td style="font-weight:600;">{{ \Carbon\Carbon::parse($rec->date)->format('d M Y') }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($rec->date)->format('l') }}</td>
                                <td>
                                    @php $sc = ['present'=>'badge-success','absent'=>'badge-danger','late'=>'badge-warning','excused'=>'badge-accent']; @endphp
                                    <span class="badge {{ $sc[$rec->status] ?? 'badge-neutral' }}">{{ ucfirst($rec->status) }}</span>
                                </td>
                                <td class="tbl-muted">{{ $rec->note ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
