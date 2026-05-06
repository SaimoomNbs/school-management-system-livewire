<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Result Sheet Report</h1>
            <p class="page-desc">Consolidated cross-tabulation of student performance across all exams in a group.</p>
        </div>
    </div>

    <div class="card" style="padding:20px;margin-bottom:16px;">
        <div style="display:flex;gap:14px;align-items:end;">
            <div class="form-group" style="margin-bottom:0;flex:1;">
                <label class="form-label">Class</label>
                <select wire:model.live="class_id" class="form-input">
                    <option value="">— Select Class —</option>
                    @foreach ($allClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;flex:1;">
                <label class="form-label">Exam Group</label>
                <select wire:model.live="exam_group_id" class="form-input" @if(!$class_id) disabled @endif>
                    <option value="">— Select Group —</option>
                    @foreach ($examGroups as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if ($reportData)
        {{-- ── Stat cards ── --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
            <div class="stat-card" style="padding:16px;text-align:center;border-top:3px solid #64748b;">
                <p style="font-size:24px;font-weight:800;color:var(--color-text-1);">{{ count($reportData['students']) }}</p>
                <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">Evaluated Students</p>
            </div>
            <div class="stat-card" style="padding:16px;text-align:center;border-top:3px solid #16a34a;">
                <p style="font-size:24px;font-weight:800;color:#16a34a;">{{ $reportData['stats']['total_passed'] }}</p>
                <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">Total Passed</p>
            </div>
            <div class="stat-card" style="padding:16px;text-align:center;border-top:3px solid #dc2626;">
                <p style="font-size:24px;font-weight:800;color:#dc2626;">{{ $reportData['stats']['total_failed'] }}</p>
                <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">Total Failed</p>
            </div>
            <div class="stat-card" style="padding:16px;text-align:center;border-top:3px solid var(--color-accent);">
                <p style="font-size:24px;font-weight:800;color:var(--color-accent);">{{ number_format($reportData['stats']['avg_percentage'], 1) }}%</p>
                <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">Class Average</p>
            </div>
        </div>

        {{-- ── Result Sheet ── --}}
        <div class="card">
            <div class="tbl-wrap">
                <table class="tbl" style="white-space:nowrap;">
                    <thead>
                        <tr>
                            <th style="min-width:40px;">Rank</th>
                            <th style="min-width:180px;">Student</th>
                            @foreach ($reportData['exams'] as $ex)
                                <th style="text-align:center;">
                                    <div style="font-size:12px;color:var(--color-text-2);">{{ $ex->subject_name }}</div>
                                    <div style="font-size:10.5px;color:var(--color-text-4);font-weight:500;">(Pass {{ $ex->pass_marks }} / {{ $ex->total_marks }})</div>
                                </th>
                            @endforeach
                            <th style="text-align:right;">Total %</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportData['students'] as $idx => $stu)
                            <tr>
                                <td style="font-weight:800;color:var(--color-text-3);">{{ $idx + 1 }}</td>
                                <td>
                                    <div style="font-weight:600;color:var(--color-text-1);font-size:13px;">{{ $stu['name'] }}</div>
                                    <div style="font-size:11px;color:var(--color-text-4);font-family:monospace;">{{ $stu['code'] }}</div>
                                </td>
                                
                                @foreach ($reportData['exams'] as $ex)
                                    @php
                                        $res = $stu['subject_scores'][$ex->id] ?? null;
                                        $marks = $res ? (float)$res->marks : null;
                                        $isFail = $marks !== null && $marks < $ex->pass_marks;
                                    @endphp
                                    <td style="text-align:center;">
                                        @if ($res)
                                            <div style="font-weight:700;font-size:13px;color:{{ $isFail ? 'var(--color-danger)' : 'var(--color-text-1)' }};">
                                                {{ $marks }} <span style="font-size:10px;color:var(--color-text-4);margin-left:2px;">{{ $res->grade }}</span>
                                            </div>
                                        @else
                                            <span style="color:var(--color-text-4);font-size:11px;">ABS</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td style="text-align:right;font-weight:800;color:var(--color-text-1);">
                                    {{ number_format($stu['percentage'], 1) }}%
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge {{ $stu['is_failed'] ? 'badge-danger' : 'badge-success' }}">
                                        {{ $stu['is_failed'] ? 'FAILED' : 'PASSED' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ count($reportData['exams']) + 4 }}" class="tbl-empty">No results processed yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
