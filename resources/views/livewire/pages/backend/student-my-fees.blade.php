<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">My Fees & Payments</h1>
            <p class="page-desc">Financial statements and payment history.</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:32px;">
        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Total Paid</h4>
            <div style="font-size:28px;font-weight:800;color:#10b981;margin-top:8px;">{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($totalPaid, 2) }}</div>
        </div>
        <div class="stat-card">
            <h4 style="font-size:12px;color:var(--color-text-3);text-transform:uppercase;font-weight:700;">Total Unpaid (Due)</h4>
            <div style="font-size:28px;font-weight:800;color:#ef4444;margin-top:8px;">{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($totalUnpaid, 2) }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px;">
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;">Fee Invoices</h3>
            </div>
            <div class="tbl-wrap" style="border:none;border-radius:0;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $fee)
                            <tr>
                                <td style="font-weight:600;">{{ $fee->label }}</td>
                                <td>{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($fee->amount, 2) }}</td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($fee->due_date)->format('d L Y') }}</td>
                                <td>
                                    <span class="badge {{ $fee->status === 'Paid' ? 'badge-success' : ($fee->status === 'Unpaid' ? 'badge-danger' : 'badge-neutral') }}">
                                        {{ $fee->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="tbl-empty">No fee records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="padding:0;overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--color-border-lgt);background:var(--color-surface-2);">
                <h3 style="font-size:14px;font-weight:700;">Payment History</h3>
            </div>
            <div class="tbl-wrap" style="border:none;border-radius:0;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td style="color:#10b981;font-weight:700;">+{{ \App\Models\Setting::get('currency_symbol', '৳') }}{{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge badge-neutral">{{ ucfirst($payment->payment_method) }}</span></td>
                                <td class="tbl-muted">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d M, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="tbl-empty">No payments made yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
