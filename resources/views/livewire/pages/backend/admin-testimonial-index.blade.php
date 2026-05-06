<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Testimonials Management</h1>
            <p class="page-desc">Manage what students and parents say about your institution.</p>
        </div>
    </div>

    <!-- Header Settings -->
    <form wire:submit="saveSettings" class="card" style="padding:32px;max-width:800px;margin-bottom:32px;">
        <div class="form-section-title">Section Header</div>
        
        @if (session()->has('success'))
            <div style="margin-bottom: 16px; color: green; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Badge Text</label>
            <input type="text" wire:model="state.testimonial_badge" class="form-input @error('state.testimonial_badge') is-invalid @enderror">
            @error('state.testimonial_badge') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.testimonial_title" class="form-input @error('state.testimonial_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">HTML tags like <code>&lt;br/&gt;</code> and <code>&lt;span&gt;</code> are allowed.</p>
            @error('state.testimonial_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;margin-top:32px;">
            <button type="submit" class="btn-primary">Save Header</button>
        </div>
    </form>

    <!-- Testimonial CRUD Form -->
    <form wire:submit="saveTestimonial" class="card" style="padding:32px;max-width:800px;margin-bottom:32px;">
        <div class="form-section-title">{{ $isEditing ? 'Edit Testimonial' : 'Add New Testimonial' }}</div>
        
        @if (session()->has('testimonial_success'))
            <div style="margin-bottom: 16px; color: green; font-weight: 500;">
                {{ session('testimonial_success') }}
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" wire:model="name" class="form-input @error('name') is-invalid @enderror" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Subtitle (e.g., Parent, Class 10)</label>
                <input type="text" wire:model="subtitle" class="form-input @error('subtitle') is-invalid @enderror">
                @error('subtitle') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Quote / Review *</label>
            <textarea wire:model="quote" class="form-input @error('quote') is-invalid @enderror" rows="3" required></textarea>
            @error('quote') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Rating (1-5 Stars) *</label>
                <select wire:model="rating" class="form-input @error('rating') is-invalid @enderror" required>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
                @error('rating') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" wire:model="sort_order" class="form-input @error('sort_order') is-invalid @enderror">
                @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;gap:12px;margin-top:32px;">
            @if($isEditing)
                <button type="button" wire:click="cancelEdit" class="btn" style="background:#f3f4f6;padding:0.6rem 1.25rem;border-radius:6px;border:1px solid #d1d5db;">Cancel</button>
            @endif
            <button type="submit" class="btn-primary">{{ $isEditing ? 'Update' : 'Add' }} Testimonial</button>
        </div>
    </form>

    <!-- Testimonials List -->
    <div class="card" style="padding:32px;max-width:800px;">
        <div class="form-section-title">Existing Testimonials</div>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
            <thead>
                <tr style="border-bottom: 2px solid #f3f4f6; text-align: left;">
                    <th style="padding: 12px 8px; color: #6b7280;">Name</th>
                    <th style="padding: 12px 8px; color: #6b7280;">Rating</th>
                    <th style="padding: 12px 8px; color: #6b7280;">Quote</th>
                    <th style="padding: 12px 8px; color: #6b7280; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $testi)
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 12px 8px;">
                        <div style="font-weight: 600; color: #111827;">{{ $testi->name }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">{{ $testi->subtitle }}</div>
                    </td>
                    <td style="padding: 12px 8px; color: #fbbf24;">
                        {{ str_repeat('★', $testi->rating) }}
                    </td>
                    <td style="padding: 12px 8px; font-size: 0.875rem; color: #374151;">
                        {{ Str::limit($testi->quote, 50) }}
                    </td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <button wire:click="edit({{ $testi->id }})" class="btn" style="padding:0.4rem 0.8rem;border-radius:4px;border:1px solid #d1d5db;margin-right:4px;">Edit</button>
                        <button wire:click="delete({{ $testi->id }})" wire:confirm="Are you sure?" class="btn" style="padding:0.4rem 0.8rem;border-radius:4px;border:1px solid #fecaca;color:#dc2626;background:#fef2f2;">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding: 24px; text-align: center; color: #9ca3af;">No testimonials yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
