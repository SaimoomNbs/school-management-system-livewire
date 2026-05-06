<div>
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Contact Messages</h1>
            <p class="page-desc">Manage inquiries and feedback from the website.</p>
        </div>
    </div>

    <div style="max-width: 1000px;">
        
        {{-- Section Header Settings --}}
        <div class="card" style="padding: 32px; margin-bottom: 32px;">
            <div class="form-section-title" style="margin-top: 0; margin-bottom: 20px;">Section Header Settings</div>
            
            @if (session()->has('success') && !session()->has('note_success'))
                <div style="margin-bottom: 16px; color: #10b981; font-weight: 500; font-size: 13px;">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit="saveSettings">
                <div class="form-group">
                    <label class="form-label">Badge Text</label>
                    <input type="text" wire:model="state.contact_badge" class="form-input" placeholder="e.g. ✦ Get In Touch">
                    @error('state.contact_badge') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Main Title</label>
                    <textarea wire:model="state.contact_title" class="form-input" rows="3" placeholder="HTML allowed"></textarea>
                    <p style="font-size: 11px; color: var(--color-text-3); margin-top: 4px;">Use <code>&lt;br/&gt;</code> for line breaks.</p>
                    @error('state.contact_title') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div style="padding-top: 16px; border-top: 1px solid var(--color-border-lgt); display: flex; justify-content: flex-end; margin-top: 24px;">
                    <button type="submit" class="btn-primary">Save Header</button>
                </div>
            </form>
        </div>

        {{-- Messages List --}}
        <div class="card" style="padding: 32px; margin-bottom: 32px;">
            <div class="form-section-title" style="margin-top: 0; margin-bottom: 20px;">Contact Messages</div>

            <div class="tbl-toolbar" style="padding: 0 0 16px 0;">
                <div style="display:flex;gap:8px;align-items:center;">
                    @if(count($selectedRows) > 0)
                        <button wire:click="deleteSelected" 
                                wire:confirm="Are you sure you want to delete selected messages?"
                                class="btn-danger">
                            Delete Selected ({{ count($selectedRows) }})
                        </button>
                    @endif
                </div>
                <div class="tbl-count">
                    {{ $messages->total() }} total {{ Str::plural('message', $messages->total()) }}
                </div>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width: 48px;">
                                <input type="checkbox" wire:model.live="selectAll" style="cursor: pointer;">
                            </th>
                            <th>Visitor</th>
                            <th>Message Snippet</th>
                            <th>Admin Note</th>
                            <th>Date</th>
                            <th style="width: 100px;" class="tbl-actions-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $msg)
                            <tr wire:key="msg-{{ $msg->id }}">
                                <td>
                                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $msg->id }}" style="cursor: pointer;">
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--color-text-1);">{{ $msg->name }}</div>
                                    <div style="font-size: 11.5px; color: var(--color-text-3);">{{ $msg->phone }}</div>
                                </td>
                                <td>
                                    <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px; color: var(--color-text-2);" title="{{ $msg->message }}">
                                        {{ $msg->message }}
                                    </div>
                                </td>
                                <td>
                                    @if($msg->admin_note)
                                        <div style="font-size: 11.5px; color: var(--color-accent); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-style: italic;">
                                            {{ $msg->admin_note }}
                                        </div>
                                    @else
                                        <span style="font-size: 11px; color: var(--color-text-4);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 12px; color: var(--color-text-2);">{{ $msg->created_at->format('d M Y') }}</div>
                                    <div style="font-size: 10px; color: var(--color-text-4);">{{ $msg->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="tbl-actions">
                                        <button wire:click="editNote({{ $msg->id }})" class="tbl-btn" title="Edit Note">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="deleteSingle({{ $msg->id }})" 
                                                wire:confirm="Delete this message?"
                                                class="tbl-btn tbl-btn-delete" title="Delete">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
@empty
                            <tr>
                                <td colspan="6" class="tbl-empty">
                                    <p>No contact messages found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div style="padding: 20px 0 0 0; border-top: 1px solid var(--color-border-lgt); margin-top: 20px;">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Note Modal --}}
    <div x-data="{ open: false }" 
         x-on:open-modal.window="if ($event.detail == 'edit-note-modal') open = true"
         x-on:close-modal.window="if ($event.detail == 'edit-note-modal') open = false"
         x-show="open" x-cloak class="modal-backdrop">
        
        <div class="modal-box modal-box--sm" @click.outside="open = false">
            <div class="modal-body" style="padding: 32px;">
                <h3 class="modal-title">Admin Note</h3>
                <p class="modal-sub" style="margin-bottom: 20px;">Add internal comments or follow-up status.</p>

                <div class="form-group">
                    <label class="form-label">Note</label>
                    <textarea wire:model="admin_note" class="form-input" rows="5" placeholder="Write something..."></textarea>
                    @error('admin_note') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="modal-foot">
                <button @click="open = false" class="btn-outline">Cancel</button>
                <button wire:click="saveNote" class="btn-primary">Save Note</button>
            </div>
        </div>
    </div>
</div>
