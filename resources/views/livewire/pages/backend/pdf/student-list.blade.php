<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List — {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, 'Helvetica Neue', sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

        .header { padding: 18px 24px 14px; border-bottom: 2px solid #5b5ef4; display: flex; justify-content: space-between; align-items: flex-end; }
        .header-left h1 { font-size: 18px; font-weight: 800; color: #111214; letter-spacing: -.3px; }
        .header-left p  { font-size: 10px; color: #868d97; margin-top: 3px; }
        .header-right   { text-align: right; font-size: 10px; color: #868d97; line-height: 1.6; }

        .summary { display: flex; gap: 16px; padding: 12px 24px; background: #f8f9fb; border-bottom: 1px solid #e5e7eb; }
        .summary-item { font-size: 10px; color: #4b5057; }
        .summary-item strong { color: #111214; }

        table { width: 100%; border-collapse: collapse; margin: 0; }
        thead tr { background: #f1f2f5; border-bottom: 1.5px solid #e5e7eb; }
        th { padding: 8px 10px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #868d97; text-align: left; white-space: nowrap; }
        tbody tr { border-bottom: 1px solid #f0f1f4; }
        tbody tr:nth-child(even) { background: #fafafa; }
        td { padding: 7px 10px; font-size: 10.5px; color: #4b5057; vertical-align: middle; }
        td.bold { font-weight: 700; color: #111214; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 99px; font-size: 9px; font-weight: 700; }
        .badge-active   { background: #f0fdf4; color: #16a34a; }
        .badge-inactive { background: #fff0f0; color: #f03e3e; }

        .footer { padding: 12px 24px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 9px; color: #b0b7c3; margin-top: 0; }

        .table-wrap { padding: 0 24px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>{{ config('app.name', 'Academy') }}</h1>
            <p>Student List Export</p>
        </div>
        <div class="header-right">
            <div>Generated: {{ $generatedAt->format('d M Y, h:i A') }}</div>
            <div>Total Students: {{ $students->count() }}</div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">Active: <strong>{{ $students->where('status', 1)->count() }}</strong></div>
        <div class="summary-item">Inactive: <strong>{{ $students->where('status', 0)->count() }}</strong></div>
        <div class="summary-item">Classes: <strong>{{ $students->pluck('class.name')->filter()->unique()->count() }}</strong></div>
    </div>

    <div class="table-wrap" style="margin-top:14px;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Phone</th>
                    <th>Guardian</th>
                    <th>Admitted</th>
                    <th>Monthly Fee</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $i => $student)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-family:monospace;font-weight:700;color:#5b5ef4;">{{ $student->student_id }}</td>
                        <td class="bold">{{ $student->name }}</td>
                        <td>{{ $student->class?->name ?? '—' }}</td>
                        <td>{{ $student->section?->name ?? '—' }}</td>
                        <td>{{ $student->phone ?? '—' }}</td>
                        <td>{{ $student->guardian_name ?? '—' }}</td>
                        <td>{{ $student->admission_date?->format('d M Y') ?? '—' }}</td>
                        <td style="font-weight:600;">{{ $student->monthly_fee > 0 ? '৳' . number_format($student->monthly_fee, 0) : '—' }}</td>
                        <td>
                            <span class="badge {{ $student->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $student->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" style="text-align:center;padding:20px;color:#868d97;">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer" style="margin-top:20px;">
        <span>{{ config('app.name') }} — Confidential</span>
        <span>Generated on {{ $generatedAt->format('d M Y') }} at {{ $generatedAt->format('h:i A') }}</span>
    </div>

</body>
</html>
