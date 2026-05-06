<div>
    
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Teachers</h1>
            <p class="page-desc">Manage teaching staff and their login accounts.</p>
        </div>
        <a href="{{ route('admin.teachers.create') }}" wire:navigate class="btn-primary" id="btn-add-teacher">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Teacher
        </a>
    </div>

    {{-- ── Card ── --}}
    <div class="card">

        {{-- Toolbar --}}
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="tbl-search-input" placeholder="Search by name, phone, email…"
                    id="teacher-search" autocomplete="off">
            </div>
            <span class="tbl-count">{{ $teachers->total() }} {{ Str::plural('teacher', $teachers->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:52px"></th>
                        <th>Teacher</th>
                        <th>ID</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th style="width:110px" class="tbl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr wire:key="teacher-{{ $teacher->id }}">
                            {{-- Photo / Avatar --}}
                            <td style="padding-right:0;">
                                <div class="teacher-avatar">
                                    @if ($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}"
                                            alt="{{ $teacher->name }}" class="teacher-avatar-img">
                                    @else
                                        <span>{{ strtoupper(substr($teacher->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.teachers.show', $teacher) }}" wire:navigate class="teacher-name-link">
                                    {{ $teacher->name }}
                                </a>
                                <div class="tbl-muted" style="font-size:11.5px;margin-top:2px;">{{ $teacher->email }}</div>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="font-family:monospace;">{{ $teacher->teacher_id }}</span>
                            </td>
                            <td class="tbl-muted">{{ $teacher->phone ?? '—' }}</td>
                            <td class="tbl-muted">{{ $teacher->qualification ?? '—' }}</td>
                            <td class="tbl-muted">{{ $teacher->joining_date?->format('d M Y') ?? '—' }}</td>
                            <td>
                                @if ($teacher->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="tbl-actions">
                                    <a href="{{ route('admin.teachers.show', $teacher) }}" wire:navigate
                                        class="tbl-btn" title="View Profile" style="color:var(--color-text-3);">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" wire:navigate
                                        class="tbl-btn tbl-btn-edit" title="Edit">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $teacher->id }})"
                                        class="tbl-btn tbl-btn-delete" title="Delete">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="tbl-empty">
                                <svg width="42" height="42" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p>No teachers found.</p>
                                @if ($search)
                                    <p style="font-size:12px;margin-top:4px;">Try a different search term.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($teachers->hasPages())
            <div class="mt-4">
                {{ $teachers->links() }}
            </div>
        @endif
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
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeDeleteModal()" @keydown.escape.window="$wire.closeDeleteModal()">

            <div class="modal-body" style="text-align:center; padding-top:28px;">
                <div class="modal-danger-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="modal-title" style="margin-top:14px;">Delete Teacher?</h3>
                <p class="modal-sub" style="margin-top:6px;">This will also delete their login account. Attendance and payment records will be preserved.</p>
            </div>

            <div class="modal-foot" style="justify-content:center; gap:10px;">
                <button wire:click="closeDeleteModal" class="btn-outline">Keep it</button>
                <button wire:click="delete" wire:loading.attr="disabled" class="btn-danger" id="btn-confirm-delete-teacher">
                    <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                    <span wire:loading wire:target="delete">Deleting…</span>
                </button>
            </div>
        </div>
    </div>
</div>
