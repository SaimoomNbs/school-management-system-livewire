<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Result Entry</h1>
            <p class="page-desc">Enter and auto-calculate grades for students per exam subject.</p>
        </div>
    </div>

    {{-- ── Selection card ── --}}
    <div class="card" style="padding:20px;margin-bottom:16px;">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:14px;align-items:end;">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Class <span class="form-required">*</span></label>
                <select wire:model.live="class_id" class="form-input">
                    <option value="">— Select Class —</option>
                    @foreach ($allClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Exam Group <span class="form-required">*</span></label>
                <select wire:model.live="exam_group_id" class="form-input" @if (!$class_id) disabled @endif>
                    <option value="">{{ $class_id ? '— Select Group —' : '— Select Class first —' }}</option>
                    @foreach ($examGroups as $grp)
                        <option value="{{ $grp->id }}">{{ $grp->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Exam Subject <span class="form-required">*</span></label>
                <select wire:model.live="exam_id" class="form-input" @if (!$exam_group_id) disabled @endif>
                    <option value="">{{ $exam_group_id ? '— Select Exam —' : '— Select Group first —' }}</option>
                    @foreach ($exams as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->subject_name }} ({{ $ex->title }})</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="loadSheet" wire:loading.attr="disabled" class="btn-primary">
                <span wire:loading.remove wire:target="loadSheet" style="display:flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Load Sheet
                </span>
                <span wire:loading wire:target="loadSheet">Loading…</span>
            </button>
        </div>
    </div>

    {{-- ════════════════ RESULT SHEET ════════════════ --}}
    @if ($sheetLoaded && count($rows) > 0)
        <div class="card">
            <div style="padding:14px 18px;border-bottom:1px solid var(--color-border-lgt);display:flex;align-items:center;justify-content:space-between;background:var(--color-surface-2);border-radius:var(--r-lg) var(--r-lg) 0 0;">
                <div style="font-size:12.5px;color:var(--color-text-2);">
                    <strong>{{ count($rows) }}</strong> students loaded. 
                    Total Marks limit: <strong>{{ (float) $currentExam->total_marks }}</strong>, Pass limit: <strong>{{ (float) $currentExam->pass_marks }}</strong>
                </div>
                <button wire:click="autoCalculateGrades" class="btn-outline" style="padding:5px 12px;font-size:12px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Auto Compute Grades
                </button>
            </div>

            <div class="tbl-wrap">
                <table class="tbl" style="table-layout:fixed;">
                    <colgroup>
                        <col style="width:44px">
                        <col style="width:240px">
                        <col style="width:120px">
                        <col style="width:120px">
                        <col style="width:80px">
                        <col style="width:180px">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student</th>
                            <th>ID</th>
                            <th>Marks Obtained</th>
                            <th>Grade</th>
                            <th>Attachment</th>
                            <th>Remarks (Optional)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($rows); $i++)
                            <tr wire:key="res-row-{{ $rows[$i]['student_id'] }}">
                                <td style="padding-right:0;">
                                    <div class="teacher-avatar" style="width:34px;height:34px;font-size:11px;">
                                        @if ($rows[$i]['photo'])
                                            <img src="{{ asset('storage/' . $rows[$i]['photo']) }}" class="teacher-avatar-img" alt="">
                                        @else
                                            {{ strtoupper(substr($rows[$i]['name'], 0, 2)) }}
                                        @endif
                                    </div>
                                </td>
                                <td><span style="font-weight:600;color:var(--color-text-1);font-size:13px;">{{ $rows[$i]['name'] }}</span></td>
                                <td><span style="font-family:monospace;font-size:11.5px;color:var(--color-text-3);">{{ $rows[$i]['student_code'] }}</span></td>
                                <td>
                                    <input type="number" step="0.01" max="{{ $currentExam->total_marks ?? 100 }}" wire:model.blur="rows.{{ $i }}.marks" class="form-input" style="padding:4px 8px;font-size:13px;font-weight:600;" placeholder="0">
                                </td>
                                <td>
                                    @php $grade = $rows[$i]['grade']; @endphp
                                    @if($grade !== '')
                                        <div style="font-weight:800;font-size:14px;color:{{ in_array($grade, ['F', 'E']) ? 'var(--color-danger)' : 'var(--color-success)' }};">{{ $grade }}</div>
                                    @else
                                        <div style="color:var(--color-text-4);font-size:11px;">N/A</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($rows[$i]['existing_attachment'])
                                        <div style="margin-bottom:6px; display:flex; align-items:center; gap:5px;">
                                            <a href="{{ asset('storage/' . $rows[$i]['existing_attachment']) }}" target="_blank" class="btn-outline" style="padding:2px 6px; font-size:10px; text-decoration:none;">
                                                View Current
                                            </a>
                                        </div>
                                    @else
                                        <input type="file" wire:model="rows.{{ $i }}.attachment" class="form-input" style="padding:4px 8px; font-size:11px;">
                                        <div wire:loading wire:target="rows.{{ $i }}.attachment" style="font-size:10px; color:var(--color-accent);">Uploading...</div>
                                    @endif
                                </td>
                                <td>
                                    <input type="text" wire:model.blur="rows.{{ $i }}.remarks" class="form-input" style="padding:4px 8px;font-size:12px;" placeholder="Optional comment...">
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- Bottom save bar --}}
            @if ($sheetLoaded)
            <div style="padding:14px 18px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;gap:10px;background:var(--color-surface-2);border-radius:0 0 var(--r-lg) var(--r-lg);">
                <button wire:click="save" wire:loading.attr="disabled" class="btn-primary">
                    <span wire:loading.remove wire:target="save" style="display:flex;align-items:center;gap:6px;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Results
                    </span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
            @endif
        </div>

    @elseif ($sheetLoaded && count($rows) === 0)
        <div class="card">
            <div class="tbl-empty" style="padding:48px;">
                <p>No active students found.</p>
            </div>
        </div>
    @else
        <div class="card" style="padding:36px;text-align:center;">
            <svg width="52" height="52" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin:0 auto 12px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p style="font-size:14px;font-weight:600;color:var(--color-text-2);">Select Class, Group, and Subject, then click <strong>Load Sheet</strong></p>
        </div>
    @endif
</div>