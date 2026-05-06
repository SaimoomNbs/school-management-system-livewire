<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Student Dashboard</h1>
            <p class="page-desc">Overview of your attendance, fees, exams, and notifications.</p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Class Info</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div style="font-size:24px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ $stats['class_info'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Attendance</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f0fdf4;color:#10b981;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ $stats['attendance_percentage'] }}%</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Due Fees</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#fef2f2;color:#ef4444;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($stats['due_fees'], 2) }}</div>
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
                            <th>Subject</th>
                            <th>Exam Title</th>
                            <th>Date</th>
                            <th>Total Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingExams as $exam)
                            <tr>
                                <td style="font-weight:600;">{{ $exam->subject_name }}</td>
                                <td style="font-weight:600;">{{ $exam->title }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}</td>
                                <td class="tbl-muted">{{ $exam->total_marks }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="tbl-empty" style="padding:30px;">No upcoming exams.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;color:var(--color-text-1);">Recent Notifications</h3>
            </div>
            <div style="padding:10px;">
                @forelse($notifications as $notification)
                    <div style="padding:12px;border-bottom:1px solid var(--color-border-lgt);">
                        <p style="font-weight:700;color:var(--color-text-1);font-size:14px;margin-bottom:4px;">{{ $notification->title }}</p>
                        <p style="font-size:13px;color:var(--color-text-3);line-height:1.5;">{{ \Illuminate\Support\Str::limit($notification->message, 80) }}</p>
                        <p style="font-size:11px;color:var(--color-text-4);margin-top:6px;">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="tbl-empty" style="padding:30px;">No new notifications.</div>
                @endforelse
            </div>
            <div style="padding:12px 20px;background:var(--color-surface-2);border-top:1px solid var(--color-border-lgt);text-align:center;">
                <a href="{{ route('admin.notifications.index') }}" style="font-weight:600;font-size:13px;color:var(--color-accent);text-decoration:none;">View All Notifications &rarr;</a>
            </div>
        </div>
        
    </div>
</div>
