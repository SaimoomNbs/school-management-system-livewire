<div>
    {{-- ── Back navigation ── --}}
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.teachers.index') }}" wire:navigate
            style="display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:500;color:var(--color-text-3);transition:color .14s ease;"
            onmouseover="this.style.color='var(--color-text-1)'" onmouseout="this.style.color='var(--color-text-3)'">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Teachers
        </a>
    </div>

    {{-- ── Profile header card ── --}}
    <div class="card" style="padding:28px;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:22px;flex-wrap:wrap;">

            {{-- Photo / Avatar --}}
            <div class="profile-avatar-lg">
                @if ($teacher->photo)
                    <img src="{{ asset('storage/' . $teacher->photo) }}"
                        alt="{{ $teacher->name }}" class="profile-avatar-img">
                @else
                    <span>{{ strtoupper(substr($teacher->name, 0, 2)) }}</span>
                @endif
            </div>

            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px;">
                    <h1 class="page-title" style="margin:0;">{{ $teacher->name }}</h1>
                    @if ($teacher->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:14px;">
                    <span style="font-size:12.5px;color:var(--color-text-3);display:flex;align-items:center;gap:5px;">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                        <span style="font-family:monospace;font-weight:600;color:var(--color-accent);">{{ $teacher->teacher_id }}</span>
                    </span>
                    @if ($teacher->email)
                        <span style="font-size:12.5px;color:var(--color-text-3);display:flex;align-items:center;gap:5px;">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $teacher->email }}
                        </span>
                    @endif
                    @if ($teacher->phone)
                        <span style="font-size:12.5px;color:var(--color-text-3);display:flex;align-items:center;gap:5px;">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $teacher->phone }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <a href="{{ route('admin.teachers.edit', $teacher) }}" wire:navigate class="btn-primary" id="btn-edit-teacher">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- ── Info grid + Subjects ── --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

        {{-- Details card --}}
        <div class="card" style="padding:22px;">
            <div class="form-section-title" style="margin-bottom:16px;">Profile Details</div>

            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach ([
                    ['Qualification',  $teacher->qualification,   'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['Date of Birth',  $teacher->dob?->format('d F Y'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['Joining Date',   $teacher->joining_date?->format('d F Y'), 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['Address',        $teacher->address, 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as [$label, $value, $icon])
                    <div style="display:flex;gap:12px;align-items:flex-start;">
                        <div style="width:32px;height:32px;border-radius:var(--r-sm);background:var(--color-surface-2);border:1px solid var(--color-border-lgt);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="color:var(--color-text-3)"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        </div>
                        <div>
                            <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-4);">{{ $label }}</p>
                            <p style="font-size:13.5px;color:var(--color-text-1);margin-top:2px;">{{ $value ?? '—' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Assigned subjects card --}}
        <div class="card" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div class="form-section-title" style="margin:0;">Assigned Subjects</div>
                <span class="badge badge-accent">{{ $teacher->subjects->count() }}</span>
            </div>

            @if ($teacher->subjects->isEmpty())
                <div class="tbl-empty" style="padding:28px 0;">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p>No subjects assigned yet.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach ($teacher->subjects as $subject)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:var(--color-surface-2);border-radius:var(--r-sm);border:1px solid var(--color-border-lgt);">
                            <div>
                                <p style="font-size:13.5px;font-weight:600;color:var(--color-text-1);">{{ $subject->name }}</p>
                                @if ($subject->code)
                                    <p style="font-size:11.5px;color:var(--color-text-3);margin-top:2px;">Code: {{ $subject->code }}</p>
                                @endif
                            </div>
                            <span class="badge badge-accent">{{ $subject->class?->name }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
