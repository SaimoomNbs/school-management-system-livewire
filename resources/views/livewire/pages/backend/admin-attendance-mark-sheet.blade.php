<div>
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Mark Attendance</h1>
            <p class="page-desc">Select a class, section, and date to take or edit attendance.</p>
        </div>
        @if ($sheetLoaded)
            <button wire:click="save" wire:loading.attr="disabled" class="btn-primary" id="btn-save-attendance">
                <span wire:loading.remove wire:target="save" style="display:flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Save Attendance
                </span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        @endif
    </div>

    {{-- ── Selection card ── --}}
    <div class="card" style="padding:20px;margin-bottom:16px;">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:14px;align-items:end;">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Class <span class="form-required">*</span></label>
                <select wire:model.live="class_id" class="form-input" id="att-class">
                    <option value="">— Select Class —</option>
                    @foreach ($allClasses as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
                @error('class_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Section <span class="form-optional">(optional)</span></label>
                <div style="position:relative;">
                    <select wire:model.live="section_id" class="form-input" id="att-section"
                        @if (!$class_id) disabled @endif>
                        <option value="">{{ $class_id ? 'All Sections' : '— Select Class first —' }}</option>
                        @foreach ($allSections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="class_id"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 0.8s linear infinite;color:var(--color-text-3)"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Date <span class="form-required">*</span></label>
                <input type="date" wire:model.blur="date" class="form-input" id="att-date"
                    max="{{ now()->format('Y-m-d') }}">
                @error('date') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <button wire:click="loadSheet" wire:loading.attr="disabled"
                class="btn-primary" id="btn-load-sheet">
                <span wire:loading.remove wire:target="loadSheet" style="display:flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Load Sheet
                </span>
                <span wire:loading wire:target="loadSheet">Loading…</span>
            </button>
        </div>
    </div>

    {{-- ════════════════ ATTENDANCE SHEET ════════════════ --}}
    @if ($sheetLoaded && count($rows) > 0)

        {{-- Quick mark toolbar --}}
        <div class="card" style="padding:14px 18px;margin-bottom:12px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <span style="font-size:12.5px;font-weight:600;color:var(--color-text-2);">Mark All:</span>
            <div class="att-quick-group">
                <button type="button" wire:click="markAll('present')" class="att-quick-btn att-present" id="btn-mark-all-present">✓ Present</button>
                <button type="button" wire:click="markAll('absent')"  class="att-quick-btn att-absent"  id="btn-mark-all-absent">✗ Absent</button>
                <button type="button" wire:click="markAll('late')"    class="att-quick-btn att-late"    id="btn-mark-all-late">⏱ Late</button>
                <button type="button" wire:click="markAll('excused')" class="att-quick-btn att-excused" id="btn-mark-all-excused">★ Excused</button>
            </div>
            <span style="margin-left:auto;font-size:12px;color:var(--color-text-3);">
                {{ count($rows) }} student(s) —
                {{ \Carbon\Carbon::parse($date)->format('d M Y, l') }}
            </span>
        </div>

        {{-- Student table --}}
        <div class="card">
            <div class="tbl-wrap">
                <table class="tbl" style="table-layout:fixed;">
                    <colgroup>
                        <col style="width:44px">
                        <col>
                        <col style="width:100px">
                        <col style="width:80px">
                        <col style="width:240px">
                        <col style="width:180px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student</th>
                            <th>ID</th>
                            <th>Section</th>
                            <th style="text-align:center;">Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < count($rows); $i++)
                            <tr wire:key="att-row-{{ $rows[$i]['student_id'] }}">

                                {{-- Avatar --}}
                                <td style="padding-right:0;">
                                    <div class="teacher-avatar" style="width:34px;height:34px;font-size:11px;background:linear-gradient(135deg,#10b981,#059669);">
                                        @if ($rows[$i]['photo'])
                                            <img src="{{ asset('storage/' . $rows[$i]['photo']) }}" class="teacher-avatar-img" alt="">
                                        @else
                                            {{ strtoupper(substr($rows[$i]['name'], 0, 2)) }}
                                        @endif
                                    </div>
                                </td>

                                {{-- Name --}}
                                <td>
                                    <span style="font-weight:600;color:var(--color-text-1);font-size:13px;">{{ $rows[$i]['name'] }}</span>
                                </td>

                                {{-- Student ID --}}
                                <td>
                                    <span style="font-family:monospace;font-size:11.5px;color:var(--color-text-3);">{{ $rows[$i]['student_code'] }}</span>
                                </td>

                                {{-- Section --}}
                                <td class="tbl-muted">{{ $rows[$i]['section'] }}</td>

                                {{-- Status radio chips --}}
                                <td>
                                    <div class="att-chip-group">
                                        @foreach (['present' => 'P', 'absent' => 'A', 'late' => 'L', 'excused' => 'E'] as $val => $lbl)
                                            <label class="att-chip att-{{ $val }} {{ ($rows[$i]['status'] ?? 'present') === $val ? 'is-selected' : '' }}">
                                                <input type="radio"
                                                    wire:model="rows.{{ $i }}.status"
                                                    value="{{ $val }}"
                                                    class="sr-only">
                                                {{ $lbl }}
                                            </label>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Note --}}
                                <td>
                                    <input type="text"
                                        wire:model.blur="rows.{{ $i }}.note"
                                        class="att-note-input"
                                        placeholder="Optional note…">
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            {{-- Bottom save bar --}}
            <div style="padding:14px 18px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;gap:10px;background:var(--color-surface-2);border-radius:0 0 var(--r-lg) var(--r-lg);">
                <span style="font-size:12.5px;color:var(--color-text-3);align-self:center;">
                    {{ count($rows) }} students • {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                </span>
                <button wire:click="save" wire:loading.attr="disabled" class="btn-primary" id="btn-save-attendance-bottom">
                    <span wire:loading.remove wire:target="save" style="display:flex;align-items:center;gap:6px;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Attendance
                    </span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
        </div>

    @elseif ($sheetLoaded && count($rows) === 0)
        <div class="card">
            <div class="tbl-empty" style="padding:48px;">
                <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:10px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <p>No active students found for this class/section.</p>
            </div>
        </div>
    @else
        <div class="card" style="padding:36px;text-align:center;">
            <svg width="52" height="52" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin:0 auto 12px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <p style="font-size:14px;font-weight:600;color:var(--color-text-2);">Select a class and date, then click <strong>Load Sheet</strong></p>
            <p style="font-size:12.5px;color:var(--color-text-4);margin-top:4px;">Existing attendance for the chosen date will be pre-filled automatically.</p>
        </div>
    @endif
</div>
