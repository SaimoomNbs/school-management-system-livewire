<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Exams (Subjects)</h1>
            <p class="page-desc">Define individual subject exams underneath their respective generic exam groups.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Exam
        </button>
    </div>

    <div class="card">
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search title or subject...">
            </div>

            <select wire:model.live="filterClassId" class="tbl-filter-select" style="margin-left:auto;">
                <option value="">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>
            
            @if ($filterClassId)
                <select wire:model.live="filterGroupId" class="tbl-filter-select">
                    <option value="">All Groups</option>
                    @foreach ($filterGroups as $g)
                        <option value="{{ $g->id }}">{{ $g->title }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Exam Title</th>
                        <th>Class - Group</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Marks (Pass / Total)</th>
                        <th style="width:100px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exams as $ex)
                        <tr>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $ex->title }}</td>
                            <td>
                                <span class="badge badge-accent" style="margin-bottom:2px;">{{ $ex->class_name }}</span>
                                <div style="font-size:11.5px;color:var(--color-text-3);">{{ $ex->group_title }}</div>
                            </td>
                            <td>{{ $ex->subject_name }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($ex->exam_date)->format('d M Y') }}</td>
                            <td>
                                <span style="font-weight:600;color:var(--color-danger);">{{ $ex->pass_marks }}</span>
                                <span style="color:var(--color-text-4);margin:0 4px;">/</span>
                                <span style="font-weight:600;color:var(--color-text-1);">{{ $ex->total_marks }}</span>
                            </td>
                            <td style="text-align:right;">
                                <button wire:click="edit({{ $ex->id }})" class="tbl-action-btn" title="Edit">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $ex->id }})" class="tbl-action-btn" style="color:var(--color-danger);" title="Delete">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="tbl-empty">No exams found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($exams->hasPages()) <div class="mt-4">{{ $exams->links() }}</div> @endif
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Exam' : 'Add Exam' }}</h5>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Reference Class</label>
                        <select wire:model.live="formClassId" class="form-input">
                            <option value="">— Select Class —</option>
                            @foreach ($allClasses as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    @if ($formClassId)
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Exam Group <span class="form-required">*</span></label>
                                <select wire:model="exam_group_id" class="form-input @error('exam_group_id') is-invalid @enderror">
                                    <option value="">— Select Group —</option>
                                    @foreach ($formGroups as $g)
                                        <option value="{{ $g->id }}">{{ $g->title }}</option>
                                    @endforeach
                                </select>
                                @error('exam_group_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Subject <span class="form-required">*</span></label>
                                <select wire:model="subject_id" class="form-input @error('subject_id') is-invalid @enderror">
                                    <option value="">— Select Subject —</option>
                                    @foreach ($formSubjects as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Specific Title <span class="form-optional">(e.g. Mathematics Part 1)</span></label>
                            <input type="text" wire:model="title" class="form-input @error('title') is-invalid @enderror">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Exam Date <span class="form-required">*</span></label>
                            <input type="date" wire:model="exam_date" class="form-input @error('exam_date') is-invalid @enderror">
                            @error('exam_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Total Marks <span class="form-required">*</span></label>
                                <input type="number" wire:model="total_marks" class="form-input @error('total_marks') is-invalid @enderror">
                                @error('total_marks') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pass Marks <span class="form-required">*</span></label>
                                <input type="number" wire:model="pass_marks" class="form-input @error('pass_marks') is-invalid @enderror">
                                @error('pass_marks') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-outline">Cancel</button>
                    <button wire:click="save" class="btn-primary" @if(!$formClassId) disabled style="opacity:0.6" @endif>Save Changes</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Exam</h5>
                    <button wire:click="closeDeleteModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" style="padding:24px 20px;">
                    <p style="font-size:14px;color:var(--color-text-2);">Are you sure? Results associated with this specific exam will be wiped permanently. This action is irreversible.</p>
                </div>
                <div class="modal-footer" style="justify-content:flex-end;">
                    <button wire:click="closeDeleteModal" class="btn-outline">Cancel</button>
                    <button wire:click="delete" class="btn-primary" style="background:var(--color-danger);border-color:var(--color-danger);">Delete Exam</button>
                </div>
            </div>
        </div>
    @endif
</div>
