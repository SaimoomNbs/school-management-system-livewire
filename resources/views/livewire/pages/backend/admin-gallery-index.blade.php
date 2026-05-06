<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Gallery</h1>
            <p class="page-desc">Upload public assets tied directly to categorization.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Image
        </button>
    </div>

    <!-- Header Settings -->
    <form wire:submit="saveSettings" class="card" style="padding:32px;margin-bottom:32px;">
        <div class="form-section-title">Section Header on Homepage</div>
        
        @if (session()->has('settings_success'))
            <div style="margin-bottom: 16px; color: green; font-weight: 500;">
                {{ session('settings_success') }}
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Badge Text</label>
            <input type="text" wire:model="state.gallery_badge" class="form-input @error('state.gallery_badge') is-invalid @enderror" placeholder="e.g., ✦ School Gallery">
            @error('state.gallery_badge') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.gallery_title" class="form-input @error('state.gallery_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags like <code>&lt;span class="dot text-yellow-400"&gt;.&lt;/span&gt;</code> for styling.</p>
            @error('state.gallery_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea wire:model="state.gallery_desc" class="form-input @error('state.gallery_desc') is-invalid @enderror" rows="2"></textarea>
            @error('state.gallery_desc') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;margin-top:32px;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="saveSettings">
                <span wire:loading.remove wire:target="saveSettings">Save Gallery Header</span>
                <span wire:loading wire:target="saveSettings">Saving...</span>
            </button>
        </div>
    </form>

    <div class="card">
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap" style="max-width:300px;">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search category or title...">
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:80px;">Thumbnail</th>
                        <th>Category</th>
                        <th>Title / Description</th>
                        <th>Upload Date</th>
                        <th style="width:100px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($galleries as $gal)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $gal->image) }}" class="teacher-avatar-img" style="border-radius:4px;width:60px;height:40px;object-fit:cover;">
                            </td>
                            <td><span class="badge badge-accent">{{ $gal->category }}</span></td>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $gal->title }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($gal->created_at)->format('d M Y') }}</td>
                            <td style="text-align:right;">
                                <button wire:click="confirmDelete({{ $gal->id }})" class="tbl-action-btn" style="color:var(--color-danger);" title="Delete">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tbl-empty">No gallery assets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($galleries->hasPages()) <div class="mt-4">{{ $galleries->links() }}</div> @endif
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Image</h5>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Category <span class="form-required">*</span></label>
                        <input type="text" wire:model="category" list="cat-list" class="form-input @error('category') is-invalid @enderror" placeholder="e.g. Sports Day 2025">
                        <datalist id="cat-list">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('category') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title <span class="form-optional">(Optional)</span></label>
                        <input type="text" wire:model="title" class="form-input @error('title') is-invalid @enderror">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Target File <span class="form-required">*</span></label>
                        <input type="file" wire:model="image" class="form-input">
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                        
                        @if ($image)
                            <div style="margin-top:10px;"><img src="{{ $image->temporaryUrl() }}" style="max-width:100%;border-radius:4px;box-shadow:var(--shadow-sm);"></div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-outline">Cancel</button>
                    <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save, image">Upload Image</span>
                        <span wire:loading wire:target="save">Saving...</span>
                        <span wire:loading wire:target="image">Uploading...</span>
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
                    <h5 class="modal-title">Delete Entry</h5>
                    <button wire:click="closeDeleteModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" style="padding:24px 20px;">
                    <p style="font-size:14px;color:var(--color-text-2);">This image asset will be removed entirely from the generic viewing catalog directly.</p>
                </div>
                <div class="modal-footer" style="justify-content:flex-end;">
                    <button wire:click="closeDeleteModal" class="btn-outline">Cancel</button>
                    <button wire:click="delete" class="btn-primary" style="background:var(--color-danger);border-color:var(--color-danger);">Delete Image</button>
                </div>
            </div>
        </div>
    @endif
</div>
