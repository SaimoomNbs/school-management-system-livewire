<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">About Section Settings</h1>
            <p class="page-desc">Modify the dynamic content of the public homepage about section.</p>
        </div>
    </div>

    <form wire:submit="save" class="card" style="padding:32px;max-width:800px;">
        
        <div class="form-section-title">Main Content</div>
        
        <div class="form-group">
            <label class="form-label">Badge Text</label>
            <input type="text" wire:model="state.about_badge" class="form-input @error('state.about_badge') is-invalid @enderror" placeholder="e.g., ✦ About Our School">
            @error('state.about_badge') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.about_title" class="form-input @error('state.about_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags like <code>&lt;br/&gt;</code> and <code>&lt;span class="text-teal-700"&gt;</code> for styling.</p>
            @error('state.about_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea wire:model="state.about_description" class="form-input @error('state.about_description') is-invalid @enderror" rows="4"></textarea>
            @error('state.about_description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-section-title" style="margin-top:32px;">Mission & Vision</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Mission Title</label>
                <input type="text" wire:model="state.about_mission_title" class="form-input @error('state.about_mission_title') is-invalid @enderror">
                @error('state.about_mission_title') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Vision Title</label>
                <input type="text" wire:model="state.about_vision_title" class="form-input @error('state.about_vision_title') is-invalid @enderror">
                @error('state.about_vision_title') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Mission Description</label>
                <textarea wire:model="state.about_mission_desc" class="form-input @error('state.about_mission_desc') is-invalid @enderror" rows="3"></textarea>
                @error('state.about_mission_desc') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Vision Description</label>
                <textarea wire:model="state.about_vision_desc" class="form-input @error('state.about_vision_desc') is-invalid @enderror" rows="3"></textarea>
                @error('state.about_vision_desc') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-section-title" style="margin-top:32px;">Call to Action Button</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Button Text</label>
                <input type="text" wire:model="state.about_btn_text" class="form-input @error('state.about_btn_text') is-invalid @enderror">
                @error('state.about_btn_text') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Button Link</label>
                <input type="text" wire:model="state.about_btn_link" class="form-input @error('state.about_btn_link') is-invalid @enderror">
                @error('state.about_btn_link') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-section-title" style="margin-top:32px;">Left Visual Block</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">School Name</label>
                <input type="text" wire:model="state.about_school_name" class="form-input @error('state.about_school_name') is-invalid @enderror">
                @error('state.about_school_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Established Text</label>
                <input type="text" wire:model="state.about_school_established" class="form-input @error('state.about_school_established') is-invalid @enderror">
                @error('state.about_school_established') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">School Logo / Emoji Image</label>
            <input type="file" wire:model="about_school_image_file" class="form-input @error('about_school_image_file') is-invalid @enderror" accept="image/*">
            <p class="text-xs text-gray-500 mt-1">If no image is uploaded, it defaults to the 🏫 emoji.</p>
            @error('about_school_image_file') <p class="form-error">{{ $message }}</p> @enderror
            @if (isset($state['about_school_image_path']) && $state['about_school_image_path'])
                <div style="margin-top:8px;">
                    <img src="{{ asset('storage/' . $state['about_school_image_path']) }}" style="height:64px; border-radius:4px;">
                </div>
            @endif
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Floating Badge Value</label>
                <input type="text" wire:model="state.about_school_badge_value" class="form-input @error('state.about_school_badge_value') is-invalid @enderror">
                @error('state.about_school_badge_value') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Floating Badge Label</label>
                <input type="text" wire:model="state.about_school_badge_label" class="form-input @error('state.about_school_badge_label') is-invalid @enderror">
                @error('state.about_school_badge_label') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Save About Section</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
