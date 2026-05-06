<div>
    @push('title'){{ __('Classes') }}@endpush

    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Classes</h1>
            <p class="page-desc">Manage all academic classes in the system.</p>
        </div>
        <button wire:click="openCreate" class="btn-primary" id="btn-add-class">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Class
        </button>
    </div>

    {{-- ── Card ── --}}
    <div class="card">

        {{-- Search bar --}}
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="tbl-search-input" placeholder="Search classes…" id="class-search" autocomplete="off">
            </div>
            <span class="tbl-count">{{ $classes->total() }} {{ Str::plural('class', $classes->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Name</th>
                        <th>Sections</th>
                        <th>Subjects</th>
                        <th>Students</th>
                        <th style="width:110px">Created</th>
                        <th style="width:90px" class="tbl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $class)
                        <tr wire:key="class-{{ $class->id }}">
                            <td class="tbl-muted">{{ $loop->iteration + ($classes->currentPage() - 1) * $classes->perPage() }}</td>
                            <td>
                                <a href="{{ route('admin.students.index', ['class_id' => $class->id]) }}" 
                                   style="text-decoration:none;"
                                   wire:navigate>
                                    <span class="tbl-bold">{{ $class->name }}</span>
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-accent">{{ $class->sections_count ?? $class->sections()->count() }}</span>
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ $class->subjects()->count() }}</span>
                            </td>
                            <td>
                                <span class="badge badge-neutral hover-badge" >
                                    {{ $class->students()->count() }}
                                </span>
                            </td>
                            <td class="tbl-muted">{{ $class->created_at?->format('d M Y') }}</td>
                            <td>
                                <div class="tbl-actions">
                                    <button wire:click="openEdit({{ $class->id }})" class="tbl-btn tbl-btn-edit" title="Edit">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $class->id }})" class="tbl-btn tbl-btn-delete" title="Delete">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="tbl-empty">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p>No classes found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($classes->hasPages())
            <div class="mt-4">
                {{ $classes->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         CREATE / EDIT MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="modal-box"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeModal()"
            @keydown.escape.window="$wire.closeModal()">

            {{-- Header --}}
            <div class="modal-head">
                <div>
                    <h3 class="modal-title">{{ $editingId ? 'Edit Class' : 'Add New Class' }}</h3>
                    <p class="modal-sub">{{ $editingId ? 'Update the class name below.' : 'Enter the new class name below.' }}</p>
                </div>
                <button wire:click="closeModal" class="modal-close">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body">
                {{-- Name --}}
                <div class="form-group">
                    <label for="class-name" class="form-label">Class Name <span class="form-required">*</span></label>
                    <input type="text" id="class-name" wire:model="name"
                        class="form-input @error('name') is-invalid @enderror"
                        placeholder="e.g. Class 9, Grade A…"
                        autofocus>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="modal-foot">
                <button wire:click="closeModal" class="btn-outline">Cancel</button>
                <button wire:click="save" wire:loading.attr="disabled" class="btn-primary" id="btn-save-class">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update Class' : 'Create Class' }}</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         DELETE CONFIRM MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showDeleteModal') }"
        x-show="open"
        x-cloak
        class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        <div class="modal-box modal-box--sm"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeDeleteModal()">

            <div class="modal-body" style="text-align:center; padding-top:28px;">
                <div class="modal-danger-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="modal-title" style="margin-top:14px;">Delete Class?</h3>
                <p class="modal-sub" style="margin-top:6px;">This will also remove all linked sections, students and exam groups. This action cannot be undone.</p>
            </div>

            <div class="modal-foot" style="justify-content:center; gap:10px;">
                <button wire:click="closeDeleteModal" class="btn-outline">Keep it</button>
                <button wire:click="delete" wire:loading.attr="disabled" class="btn-danger" id="btn-confirm-delete-class">
                    <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                    <span wire:loading wire:target="delete">Deleting…</span>
                </button>
            </div>
        </div>
    </div>
</div>
