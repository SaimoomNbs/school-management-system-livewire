<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Exam Groups</h1>
            <p class="page-desc">Manage major examination phases (e.g. Mid-term, Finals) assigned per class.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Group
        </button>
    </div>

    <div class="card">
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search title...">
            </div>

            <select wire:model.live="filterClassId" class="tbl-filter-select" style="margin-left:auto;">
                <option value="">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th style="width:100px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $grp)
                        <tr>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $grp->title }}</td>
                            <td><span class="badge badge-accent">{{ $grp->class_name }}</span></td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($grp->start_date)->format('d M Y') }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($grp->end_date)->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <button wire:click="edit({{ $grp->id }})" class="tbl-action-btn" title="Edit">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $grp->id }})" class="tbl-action-btn" style="color:var(--color-danger);" title="Delete">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tbl-empty">No exam groups found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($groups->hasPages()) <div class="mt-4">{{ $groups->links() }}</div> @endif
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Exam Group' : 'Add Exam Group' }}</h5>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Class <span class="form-required">*</span></label>
                        <select wire:model="class_id" class="form-input @error('class_id') is-invalid @enderror">
                            <option value="">— Select —</option>
                            @foreach ($allClasses as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Exam Group Title <span class="form-required">*</span></label>
                        <input type="text" wire:model="title" class="form-input @error('title') is-invalid @enderror" placeholder="e.g. Final Examination 2025">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Date <span class="form-required">*</span></label>
                            <input type="date" wire:model="start_date" class="form-input @error('start_date') is-invalid @enderror">
                            @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date <span class="form-required">*</span></label>
                            <input type="date" wire:model="end_date" class="form-input @error('end_date') is-invalid @enderror">
                            @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-outline">Cancel</button>
                    <button wire:click="save" class="btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Exam Group</h5>
                    <button wire:click="closeDeleteModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" style="padding:24px 20px;">
                    <p style="font-size:14px;color:var(--color-text-2);">Are you sure? This will remove the exam group. All child exams and their associated results might be lost if cascade logic applies. This action is irreversible.</p>
                </div>
                <div class="modal-footer" style="justify-content:flex-end;">
                    <button wire:click="closeDeleteModal" class="btn-outline">Cancel</button>
                    <button wire:click="delete" class="btn-primary" style="background:var(--color-danger);border-color:var(--color-danger);">Delete Group</button>
                </div>
            </div>
        </div>
    @endif
</div>
