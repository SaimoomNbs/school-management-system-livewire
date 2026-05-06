<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">My Result Cards</h1>
            <p class="page-desc">View your cumulative academic performance across semesters.</p>
        </div>
    </div>

    @if (empty($reportCards))
        <div class="card tbl-empty" style="padding:48px;">
            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:var(--color-text-4);margin-bottom:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <p style="font-size:15px;font-weight:600;color:var(--color-text-2);">No Results Found</p>
            <p style="font-size:13px;color:var(--color-text-3);margin-top:4px;">When exams are published, they will appear here.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:24px;">
            @foreach ($reportCards as $gid => $card)
                <div class="card" style="overflow:hidden;">
                    <div style="background:var(--color-surface-2);padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);display:flex;justify-content:space-between;align-items:center;">
                        <h3 style="font-size:16px;font-weight:800;color:var(--color-accent);">{{ $card['group_title'] }}</h3>
                        @if(!$card['all_published'])
                            <span class="badge badge-neutral" style="padding:4px 10px;font-size:12px;">
                                RESULTS PENDING
                            </span>
                        @else
                            <span class="badge {{ $card['has_failed'] ? 'badge-danger' : 'badge-success' }}" style="padding:4px 10px;font-size:12px;">
                                {{ $card['has_failed'] ? 'FAILED' : 'PASSED' }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="tbl-wrap">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Max Marks</th>
                                    <th>Pass Marks</th>
                                    <th>Obtained</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($card['exams'] as $ex)
                                    @php
                                        $isFail = $ex->marks !== null && ($ex->marks < $ex->pass_marks || $ex->grade === 'F');
                                    @endphp
                                    <tr>
                                        <td style="font-weight:600;color:var(--color-text-1);">{{ $ex->subject_name }}</td>
                                        <td class="tbl-muted">{{ \Carbon\Carbon::parse($ex->exam_date)->format('d M Y') }}</td>
                                        <td class="tbl-muted">{{ $ex->total_marks }}</td>
                                        <td class="tbl-muted">{{ $ex->pass_marks }}</td>
                                        <td style="font-weight:700;color:{{ $ex->marks === null ? 'var(--color-text-3)' : ($isFail ? 'var(--color-danger)' : 'var(--color-text-1)') }};">
                                            {{ $ex->marks ?? 'Pending' }}
                                        </td>
                                        <td>
                                            <span style="font-weight:800;color:{{ $ex->marks === null ? 'var(--color-text-3)' : ($isFail ? 'var(--color-danger)' : 'var(--color-success)') }};">
                                                {{ $ex->grade ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="tbl-muted">{{ $ex->remarks ?? '—' }}</td>
                                        <td>
                                            @if($ex->attachment)
                                                <a href="{{ asset('storage/' . $ex->attachment) }}" download class="btn-outline" style="padding:4px 8px; font-size:11px; display:inline-flex; align-items:center; gap:4px; text-decoration:none; color:var(--color-accent); border-color:var(--color-accent-dim);">
                                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Download
                                                </a>
                                            @else
                                                <span style="color:var(--color-text-4); font-size:11px;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="padding:14px 20px;background:var(--color-surface);border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;gap:32px;">
                        <div style="text-align:right;">
                            <div style="font-size:11.5px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Total Score</div>
                            <div style="font-size:16px;font-weight:800;color:var(--color-text-1);">{{ $card['obtained'] }} / {{ $card['total_marks'] }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:11.5px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Overall Percentage</div>
                            <div style="font-size:16px;font-weight:800;color:var(--color-accent);">{{ $card['total_marks'] > 0 ? number_format(($card['obtained'] / $card['total_marks']) * 100, 1) : 0 }}%</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>