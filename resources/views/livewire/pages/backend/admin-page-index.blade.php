<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">CMS Pages</h1>
            <p class="page-desc">Govern static content availability securely exposed natively within frontend paths.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Page
        </button>
    </div>

    <div class="card">
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap" style="max-width:300px;">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search page block...">
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Page Title</th>
                        <th>Slug Block / Route</th>
                        <th>Status</th>
                        <th>Last Edited</th>
                        <th style="width:100px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $pg)
                        <tr>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $pg->title }}</td>
                            <td style="font-family:monospace;font-size:11.5px;color:var(--color-text-3);">{{ $pg->slug }}</td>
                            <td>
                                <span class="badge {{ $pg->status ? 'badge-success' : 'badge-neutral' }}">{{ $pg->status ? 'Published' : 'Draft' }}</span>
                            </td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($pg->updated_at)->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <button wire:click="edit({{ $pg->id }})" class="tbl-action-btn" title="Edit">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $pg->id }})" class="tbl-action-btn" style="color:var(--color-danger);" title="Delete">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tbl-empty">No CMS pages found internally.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pages->hasPages()) <div class="mt-4">{{ $pages->links() }}</div> @endif
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal-dialog" style="max-width:700px;">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Block' : 'Add Content' }}</h5>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Page Title <span class="form-required">*</span></label>
                            <input type="text" wire:model.blur="title" class="form-input @error('title') is-invalid @enderror">
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">URI Slug <span class="form-required">*</span></label>
                            <input type="text" wire:model="slug" class="form-input @error('slug') is-invalid @enderror" placeholder="e.g. about-us">
                            @error('slug') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Visibility Status</label>
                        <select wire:model="status" class="form-input" style="max-width:150px;">
                            <option value="1">Published</option>
                            <option value="0">Draft Hidden</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Body Markdown/HTML Node <span class="form-required">*</span></label>
                        <textarea wire:model="content" class="form-input @error('content') is-invalid @enderror" rows="12" style="font-family:monospace;font-size:13px;"></textarea>
                        @error('content') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-outline">Cancel</button>
                    <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Publish Content</span>
                        <span wire:loading wire:target="save">Committing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">Destroy Page Configuration</h5>
                    <button wire:click="closeDeleteModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" style="padding:24px 20px;">
                    <p style="font-size:14px;color:var(--color-text-2);">Deleting this page will break any frontal hyperlinking pointing directly towards its specific slug natively.</p>
                </div>
                <div class="modal-footer" style="justify-content:flex-end;">
                    <button wire:click="closeDeleteModal" class="btn-outline">Cancel</button>
                    <button wire:click="delete" class="btn-primary" style="background:var(--color-danger);border-color:var(--color-danger);">Destroy Slug Node</button>
                </div>
            </div>
        </div>
    @endif
</div>
