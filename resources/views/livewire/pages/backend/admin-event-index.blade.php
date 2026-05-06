<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Events</h1>
            <p class="page-desc">List and manage upcoming academic and social events.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Event
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
            <input type="text" wire:model="state.event_badge" class="form-input @error('state.event_badge') is-invalid @enderror" placeholder="e.g., ✦ All Events">
            @error('state.event_badge') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.event_title" class="form-input @error('state.event_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags like <code>&lt;span class="dot text-yellow-400"&gt;.&lt;/span&gt;</code> for styling.</p>
            @error('state.event_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea wire:model="state.event_desc" class="form-input @error('state.event_desc') is-invalid @enderror" rows="2"></textarea>
            @error('state.event_desc') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;margin-top:32px;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="saveSettings">
                <span wire:loading.remove wire:target="saveSettings">Save Event Header</span>
                <span wire:loading wire:target="saveSettings">Saving...</span>
            </button>
        </div>
    </form>

    <div class="card">
        <div class="tbl-toolbar">
            <div class="tbl-search-wrap" style="max-width:300px;">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" class="tbl-search-input" placeholder="Search event title...">
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:60px;">Image</th>
                        <th>Title</th>
                        <th>Event Date</th>
                        <th style="max-width:300px;">Description</th>
                        <th style="width:100px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $ev)
                        <tr>
                            <td>
                                @if ($ev->image)
                                    <img src="{{ asset('storage/' . $ev->image) }}" class="teacher-avatar-img" style="border-radius:4px;width:40px;height:40px;object-fit:cover;">
                                @else
                                    <div style="width:40px;height:40px;background:var(--color-surface-2);border-radius:4px;display:flex;align-items:center;justify-content:center;color:var(--color-text-4);">
                                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:600;color:var(--color-text-1);">{{ $ev->title }}</td>
                            <td class="tbl-muted">{{ \Carbon\Carbon::parse($ev->event_date)->format('d M Y') }}</td>
                            <td class="tbl-muted" style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ev->description }}</td>
                            <td style="text-align:right;">
                                <button wire:click="edit({{ $ev->id }})" class="tbl-action-btn" title="Edit">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $ev->id }})" class="tbl-action-btn" style="color:var(--color-danger);" title="Delete">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="tbl-empty">No events scheduled.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($events->hasPages()) <div class="mt-4">{{ $events->links() }}</div> @endif
    </div>

    {{-- Form Modal --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal-dialog" style="max-width:600px;">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Event' : 'Add Event' }}</h5>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Event Title <span class="form-required">*</span></label>
                        <input type="text" wire:model="title" class="form-input @error('title') is-invalid @enderror">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Date <span class="form-required">*</span></label>
                        <input type="date" wire:model="event_date" class="form-input @error('event_date') is-invalid @enderror">
                        @error('event_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description <span class="form-required">*</span></label>
                        <textarea wire:model="description" class="form-input @error('description') is-invalid @enderror" rows="4"></textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Image</label>
                        <input type="file" wire:model="image" class="form-input">
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                        @if ($image)
                            <div style="margin-top:10px;"><img src="{{ $image->temporaryUrl() }}" style="max-width:200px;border-radius:4px;"></div>
                        @elseif ($existing_image)
                            <div style="margin-top:10px;"><img src="{{ asset('storage/' . $existing_image) }}" style="max-width:200px;border-radius:4px;"></div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-outline">Cancel</button>
                    <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save, image">Save Event</span>
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
                    <h5 class="modal-title">Delete Event</h5>
                    <button wire:click="closeDeleteModal" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" style="padding:24px 20px;">
                    <p style="font-size:14px;color:var(--color-text-2);">Determine whether this administrative post should be securely destroyed. This action is irreversible.</p>
                </div>
                <div class="modal-footer" style="justify-content:flex-end;">
                    <button wire:click="closeDeleteModal" class="btn-outline">Cancel</button>
                    <button wire:click="delete" class="btn-primary" style="background:var(--color-danger);border-color:var(--color-danger);">Delete Event</button>
                </div>
            </div>
        </div>
    @endif
</div>
