<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-desc">Institutional metrics securely evaluated tracking daily analytics.</p>
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
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Employed Teachers</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f5f3ff;color:#8b5cf6;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_teachers']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Gross Revenue</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#f0fdf4;color:#10b981;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Account Users</h4>
            <div style="display:flex;align-items:center;gap:12px;margin-top:8px;">
                <div style="width:40px;height:40px;border-radius:8px;background:#fffbeb;color:#f59e0b;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--color-text-1);letter-spacing:-0.5px;">{{ number_format($stats['total_users']) }}</div>
            </div>
        </div>
    </div>

    {{-- Activity Split Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;">
        
        {{-- Recent Payments --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;color:var(--color-text-1);">Recent Transactions</h3>
            </div>
            <div class="tbl-wrap" style="border:none;border-radius:0;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_activity['payments'] as $payment)
                            <tr>
                                <td style="font-weight:600;">{{ $payment->student_name }}</td>
                                <td style="color:#10b981;font-weight:700;">+{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($payment->amount) }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M, h:i A') }}</td>
                                <td><span class="badge badge-neutral">{{ ucfirst($payment->payment_method) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="tbl-empty" style="padding:30px;">No recent transactions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Enrollments --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;color:var(--color-text-1);">Latest Enrollments</h3>
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
