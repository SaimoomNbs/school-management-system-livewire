<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Why Choose Us Settings</h1>
            <p class="page-desc">Modify the dynamic content of the public homepage why choose us section.</p>
        </div>
    </div>

    <!-- Header Settings -->
    <form wire:submit="saveSettings" class="card" style="padding:32px;max-width:800px;margin-bottom:32px;">
        <div class="form-section-title">Section Header</div>
        
        <div class="form-group">
            <label class="form-label">Badge Text</label>
            <input type="text" wire:model="state.why_badge" class="form-input @error('state.why_badge') is-invalid @enderror" placeholder="e.g., ✦ Why Choose Us">
            @error('state.why_badge') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.why_title" class="form-input @error('state.why_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags like <code>&lt;br/&gt;</code> and <code>&lt;span class="text-yellow-400"&gt;</code> for styling.</p>
            @error('state.why_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;margin-top:32px;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="saveSettings">
                <span wire:loading.remove wire:target="saveSettings">Save Header Settings</span>
                <span wire:loading wire:target="saveSettings">Saving...</span>
            </button>
        </div>
    </form>

    <!-- Card Form -->
    <form wire:submit="saveCard" class="card" style="padding:32px;max-width:800px;margin-bottom:32px;">
        <div class="form-section-title">{{ $isEditing ? 'Edit Feature Card' : 'Add New Feature Card' }}</div>
        
        @if (session()->has('card_success'))
            <div style="margin-bottom: 16px; color: green; font-weight: 500;">
                {{ session('card_success') }}
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input type="text" wire:model="cardTitle" class="form-input @error('cardTitle') is-invalid @enderror" required>
                @error('cardTitle') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Icon (Emoji or Text)</label>
                <input type="text" wire:model="cardIcon" class="form-input @error('cardIcon') is-invalid @enderror" placeholder="e.g., 👩‍🏫">
                @error('cardIcon') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description *</label>
            <textarea wire:model="cardDesc" class="form-input @error('cardDesc') is-invalid @enderror" rows="3" required></textarea>
            @error('cardDesc') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" wire:model="cardSortOrder" class="form-input @error('cardSortOrder') is-invalid @enderror" placeholder="0">
                @error('cardSortOrder') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Background Image</label>
                <input type="file" wire:model="cardImgFile" class="form-input @error('cardImgFile') is-invalid @enderror" accept="image/*">
                @error('cardImgFile') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;gap:12px;margin-top:32px;">
            @if($isEditing)
            <button type="button" wire:click="cancelEdit" class="btn" style="background:var(--color-bg-base);border:1px solid var(--color-border-lgt);padding:0.6rem 1.25rem;border-radius:6px;cursor:pointer;">
                Cancel
            </button>
            @endif
            <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="saveCard">
                <span wire:loading.remove wire:target="saveCard">{{ $isEditing ? 'Update Card' : 'Add Card' }}</span>
                <span wire:loading wire:target="saveCard">Saving...</span>
            </button>
        </div>
    </form>

    <!-- Cards List -->
    <div class="card" style="padding:32px;max-width:800px;">
        <div class="form-section-title">Existing Feature Cards</div>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border-lgt); text-align: left;">
                    <th style="padding: 12px 8px; color: var(--color-text-sec);">Order</th>
                    <th style="padding: 12px 8px; color: var(--color-text-sec);">Icon</th>
                    <th style="padding: 12px 8px; color: var(--color-text-sec);">Title</th>
                    <th style="padding: 12px 8px; color: var(--color-text-sec);">Image</th>
                    <th style="padding: 12px 8px; color: var(--color-text-sec); text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cards as $card)
                <tr style="border-bottom: 1px solid var(--color-border-lgt);">
                    <td style="padding: 12px 8px;">{{ $card->sort_order }}</td>
                    <td style="padding: 12px 8px; font-size: 1.5rem;">{{ $card->icon }}</td>
                    <td style="padding: 12px 8px;">
                        <strong>{{ $card->title }}</strong>
                        <p style="font-size: 0.8rem; color: var(--color-text-sec); margin-top: 4px;">{{ Str::limit($card->description, 50) }}</p>
                    </td>
                    <td style="padding: 12px 8px;">
                        @if($card->image_path)
                        <img src="{{ asset('storage/' . $card->image_path) }}" style="height:40px; border-radius:4px; object-fit:cover; width: 60px;">
                        @else
                        <span style="font-size: 0.8rem; color: var(--color-text-sec);">None</span>
                        @endif
                    </td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <button wire:click="editCard({{ $card->id }})" class="btn" style="background:var(--color-bg-base);border:1px solid var(--color-border-lgt);padding:0.4rem 0.8rem;border-radius:4px;cursor:pointer;font-size:0.875rem;margin-right:4px;">Edit</button>
                        <button wire:click="deleteCard({{ $card->id }})" wire:confirm="Are you sure you want to delete this card?" class="btn" style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;padding:0.4rem 0.8rem;border-radius:4px;cursor:pointer;font-size:0.875rem;">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 24px 8px; text-align: center; color: var(--color-text-sec);">No feature cards added yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
