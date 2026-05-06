<div>

    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Students</h1>
            <p class="page-desc">Manage enrolled students, their accounts and assignments.</p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <button wire:click="exportPdf" wire:loading.attr="disabled" wire:target="exportPdf" class="btn-outline"
                id="btn-export-pdf" title="Export current filter to PDF">
                <span wire:loading.remove wire:target="exportPdf" style="display:flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </span>
                <span wire:loading wire:target="exportPdf">Generating…</span>
            </button>
            <a href="{{ route('admin.students.create') }}" wire:navigate class="btn-primary" id="btn-add-student">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Student
            </a>
        </div>
    </div>

    {{-- ── Card ── --}}
    <div class="card">

        {{-- Toolbar --}}
        <div class="tbl-toolbar" style="flex-wrap:wrap;gap:8px;">

            {{-- Search --}}
            <div class="tbl-search-wrap" style="min-width:220px;flex:1;">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input"
                    placeholder="Name, ID, phone…" id="student-search" autocomplete="off">
            </div>

            {{-- Class filter --}}
            <select wire:model.live="filterClassId" class="tbl-filter-select" id="student-filter-class">
                <option value="">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>

            {{-- Section filter (only shows when class selected) --}}
            @if ($filterClassId)
                <select wire:model.live="filterSectionId" class="tbl-filter-select" id="student-filter-section">
                    <option value="">All Sections</option>
                    @foreach ($allSections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                    @endforeach
                </select>
            @endif

            {{-- Status filter --}}
            <select wire:model.live="filterStatus" class="tbl-filter-select" id="student-filter-status">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <span class="tbl-count">{{ $students->total() }} {{ Str::plural('student', $students->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:52px"></th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Class / Section</th>
                        <!-- <th>Phone</th> -->
                        <th>Monthly Fee</th>
                        <th>Admitted</th>
                        <th>Status</th>
                        <th style="width:110px" class="tbl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr wire:key="student-{{ $student->id }}">
                            <td style="padding-right:0;">
                                <div class="teacher-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">
                                    @if ($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}"
                                            class="teacher-avatar-img">
                                    @else
                                        <span>{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.students.show', $student) }}" wire:navigate
                                    class="teacher-name-link">
                                    {{ $student->name }}
                                </a>
                                <div class="tbl-muted" style="font-size:11.5px;margin-top:2px;">{{ $student->email ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-neutral"
                                    style="font-family:monospace;">{{ $student->student_id }}</span>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:3px;">
                                    @if ($student->class)
                                        <span class="badge badge-accent"
                                            style="width:fit-content;">{{ $student->class->name }}</span>
                                    @else
                                        <span class="tbl-muted">—</span>
                                    @endif
                                    @if ($student->section)
                                        <span
                                            style="font-size:11.5px;color:var(--color-text-3);">{{ $student->section->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <!-- <td class="tbl-muted">{{ $student->phone ?? '—' }}</td> -->
                            <td style="font-weight:600;color:var(--color-text-1);">
                                {{ $student->monthly_fee > 0 ? '৳ ' . number_format($student->monthly_fee, 0) : '—' }}
                            </td>
                            <td class="tbl-muted">{{ $student->admission_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if ($student->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="tbl-actions">
                                    <a href="{{ route('admin.students.show', $student) }}" wire:navigate class="tbl-btn"
                                        title="View Profile">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" wire:navigate
                                        class="tbl-btn tbl-btn-edit" title="Edit">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if(auth()->user()?->hasRole('super_admin'))
                                        <button wire:click="confirmDelete({{ $student->id }})" class="tbl-btn tbl-btn-delete"
                                            title="Delete">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="tbl-empty">
                                <svg width="42" height="42" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p>No students found.</p>
                                @if ($search || $filterClassId || $filterStatus !== '')
                                    <p style="font-size:12px;margin-top:4px;">Try adjusting your filters.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="mt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    {{-- ══ DELETE MODAL ══ --}}
    <div x-data="{ open: @entangle('showDeleteModal') }" x-show="open" x-cloak class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="modal-box modal-box--sm" x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95" @click.outside="$wire.closeDeleteModal()"
            @keydown.escape.window="$wire.closeDeleteModal()">
            <div class="modal-body" style="text-align:center;padding-top:28px;">
                <div class="modal-danger-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="modal-title" style="margin-top:14px;">Delete Student?</h3>
                <p class="modal-sub" style="margin-top:6px;">Their login account will also be removed. Fee and payment
                    records will be preserved.</p>
            </div>
            <div class="modal-foot" style="justify-content:center;gap:10px;">
                <button wire:click="closeDeleteModal" class="btn-outline">Keep it</button>
                <button wire:click="delete" wire:loading.attr="disabled" class="btn-danger"
                    id="btn-confirm-delete-student">
                    <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                    <span wire:loading wire:target="delete">Deleting…</span>
                </button>
            </div>
        </div>
    </div>
</div>