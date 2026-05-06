<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">My Subjects & Courses</h1>
            <p class="page-desc">List of all subjects and courses you are currently enrolled in.</p>
        </div>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        <div class="tbl-wrap" style="border:none;border-radius:0;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Teacher</th>
                        <th>Teacher Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $subject->name }}</td>
                            <td style="font-family:monospace;color:var(--color-text-3);">{{ $subject->code ?? '—' }}</td>
                            <td style="font-weight:600;">{{ $subject->teacher_name ?? 'Not Assigned' }}</td>
                            <td class="tbl-muted">{{ $subject->teacher_phone ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="tbl-empty" style="padding:48px;">
                                <p style="font-size:15px;font-weight:600;color:var(--color-text-2);">No Subjects Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
