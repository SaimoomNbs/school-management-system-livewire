<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Teacher Dashboard</h1>
            <p class="page-desc">Overview of classes, exams, and recent students.</p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Total Students</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_students']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Active Classes</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f5f3ff;color:#8b5cf6;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_classes']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Total Subjects</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f0fdf4;color:#10b981;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_subjects']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Upcoming Events</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#fffbeb;color:#f59e0b;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_events']) }}</div>
            </div>
        </div>
    </div>

    {{-- Activity Split Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;">
        
        {{-- Upcoming Exams --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;color:var(--color-text-1);">Upcoming Exams</h3>
            </div>
            <div class="tbl-wrap" style="border:none;border-radius:0;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Exam Group</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingExams as $exam)
                            <tr>
                                <td style="font-weight:600;">{{ $exam->group_name ?? 'N/A' }}</td>
                                <td style="color:#3b82f6;font-weight:600;">{{ $exam->subject_name }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="tbl-empty" style="padding:30px;">No upcoming exams.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Enrollments --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;color:var(--color-text-1);">Latest Enrolled Students</h3>
            </div>
            <div class="tbl-wrap" style="border:none;border-radius:0;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Admitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_activity['students'] as $st)
                            <tr>
                                <td style="font-family:monospace;font-weight:600;color:var(--color-text-3);">{{ $st->student_id }}</td>
                                <td style="font-weight:600;color:var(--color-text-1);">{{ $st->name }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($st->admission_date)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="tbl-empty" style="padding:30px;">No enrollments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
