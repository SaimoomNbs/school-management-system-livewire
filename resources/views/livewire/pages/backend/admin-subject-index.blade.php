<div>
    @push('title'){{ __('Subjects') }}@endpush

    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Subjects</h1>
            <p class="page-desc">Manage subjects, assign teachers and link to classes.</p>
        </div>
        <button wire:click="openCreate" class="btn-primary" id="btn-add-subject">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Subject
        </button>
    </div>

    {{-- ── Card ── --}}
    <div class="card">

        {{-- Toolbar --}}
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="tbl-search-input" placeholder="Search by name or code…" id="subject-search" autocomplete="off">
            </div>

            <select wire:model.live="filterClassId" class="tbl-filter-select" id="subject-filter-class">
                <option value="">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>

            <span class="tbl-count">{{ $subjects->total() }} {{ Str::plural('subject', $subjects->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Subject</th>
                        <th>Code</th>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th style="width:110px">Created</th>
                        <th style="width:90px" class="tbl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr wire:key="subject-{{ $subject->id }}">
                            <td class="tbl-muted">{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                            <td><span class="tbl-bold">{{ $subject->name }}</span></td>
                            <td>
                                @if ($subject->code)
                                    <span class="badge badge-neutral">{{ $subject->code }}</span>
                                @else
                                    <span class="tbl-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-accent">{{ $subject->class?->name ?? '—' }}</span>
                            </td>
                            <td>
                                @if ($subject->teacher)
                                    <span class="tbl-teacher">{{ $subject->teacher->name }}</span>
                                @else
                                    <span class="tbl-muted">Unassigned</span>
                                @endif
                            </td>
                            <td class="tbl-muted">{{ $subject->created_at?->format('d M Y') }}</td>
                            <td>
                                <div class="tbl-actions">
                                    <button wire:click="openEdit({{ $subject->id }})" class="tbl-btn tbl-btn-edit" title="Edit">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $subject->id }})" class="tbl-btn tbl-btn-delete" title="Delete">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tbl-empty">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p>No subjects found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subjects->hasPages())
            <div class="mt-4">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         CREATE / EDIT MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showModal') }"
        x-show="open" x-cloak
        class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="modal-box"
            x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeModal()" @keydown.escape.window="$wire.closeModal()">

            <div class="modal-head">
                <div>
                    <h3 class="modal-title">{{ $editingId ? 'Edit Subject' : 'Add New Subject' }}</h3>
                    <p class="modal-sub">{{ $editingId ? 'Update subject details below.' : 'Fill in the subject details below.' }}</p>
                </div>
                <button wire:click="closeModal" class="modal-close">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-row">
                    {{-- Class --}}
                    <div class="form-group">
                        <label for="subject-class" class="form-label">Class <span class="form-required">*</span></label>
                        <select id="subject-class" wire:model="class_id"
                            class="form-input @error('class_id') is-invalid @enderror">
                            <option value="">— Select class —</option>
                            @foreach ($allClasses as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Teacher --}}
                    <div class="form-group">
                        <label for="subject-teacher" class="form-label">Assign Teacher <span class="form-optional">(optional)</span></label>
                        <select id="subject-teacher" wire:model="teacher_id"
                            class="form-input @error('teacher_id') is-invalid @enderror">
                            <option value="">— Unassigned —</option>
                            @foreach ($allTeachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- Name --}}
                    <div class="form-group">
                        <label for="subject-name" class="form-label">Subject Name <span class="form-required">*</span></label>
                        <input type="text" id="subject-name" wire:model="name"
                            class="form-input @error('name') is-invalid @enderror"
                            placeholder="e.g. Mathematics, Physics…">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Code --}}
                    <div class="form-group">
                        <label for="subject-code" class="form-label">Subject Code <span class="form-optional">(optional)</span></label>
                        <input type="text" id="subject-code" wire:model="code"
                            class="form-input @error('code') is-invalid @enderror"
                            placeholder="e.g. MATH-101…">
                        @error('code') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="modal-foot">
                <button wire:click="closeModal" class="btn-outline">Cancel</button>
                <button wire:click="save" wire:loading.attr="disabled" class="btn-primary" id="btn-save-subject">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update Subject' : 'Create Subject' }}</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showDeleteModal') }"
        x-show="open" x-cloak
        class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="modal-box modal-box--sm"
            x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeDeleteModal()">

            <div class="modal-body" style="text-align:center; padding-top:28px;">
                <div class="modal-danger-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="modal-title" style="margin-top:14px;">Delete Subject?</h3>
                <p class="modal-sub" style="margin-top:6px;">All linked exams and results may be affected. This cannot be undone.</p>
            </div>

            <div class="modal-foot" style="justify-content:center; gap:10px;">
                <button wire:click="closeDeleteModal" class="btn-outline">Keep it</button>
                <button wire:click="delete" wire:loading.attr="disabled" class="btn-danger" id="btn-confirm-delete-subject">
                    <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                    <span wire:loading wire:target="delete">Deleting…</span>
                </button>
            </div>
        </div>
    </div>
</div>
