<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Hero Section Settings</h1>
            <p class="page-desc">Modify the dynamic content of the public homepage hero section.</p>
        </div>
    </div>

    <form wire:submit="save" class="card" style="padding:32px;max-width:800px;">
        
        <div class="form-section-title">Main Content</div>
        
        <div class="form-group">
            <label class="form-label">Notice/Badge Text</label>
            <input type="text" wire:model="state.hero_notice" class="form-input @error('state.hero_notice') is-invalid @enderror" placeholder="e.g., ✦ Admissions Open 2025–26">
            @error('state.hero_notice') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Main Title</label>
            <input type="text" wire:model="state.hero_title" class="form-input @error('state.hero_title') is-invalid @enderror">
            <p class="text-xs text-gray-500 mt-1">You can use HTML tags like <code>&lt;br/&gt;</code> and <code>&lt;span class="text-teal-700"&gt;</code> for styling.</p>
            @error('state.hero_title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Subtitle</label>
            <textarea wire:model="state.hero_subtitle" class="form-input @error('state.hero_subtitle') is-invalid @enderror" rows="3"></textarea>
            @error('state.hero_subtitle') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-section-title" style="margin-top:32px;">Call to Action Buttons</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Primary Button Text</label>
                <input type="text" wire:model="state.hero_btn1_text" class="form-input @error('state.hero_btn1_text') is-invalid @enderror">
                @error('state.hero_btn1_text') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Primary Button Link</label>
                <input type="text" wire:model="state.hero_btn1_link" class="form-input @error('state.hero_btn1_link') is-invalid @enderror">
                @error('state.hero_btn1_link') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Secondary Button Text</label>
                <input type="text" wire:model="state.hero_btn2_text" class="form-input @error('state.hero_btn2_text') is-invalid @enderror">
                @error('state.hero_btn2_text') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Secondary Button Link</label>
                <input type="text" wire:model="state.hero_btn2_link" class="form-input @error('state.hero_btn2_link') is-invalid @enderror">
                @error('state.hero_btn2_link') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-section-title" style="margin-top:32px;">Statistics Bar</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Years of Excellence</label>
                <input type="text" wire:model="state.hero_stats_years" class="form-input @error('state.hero_stats_years') is-invalid @enderror">
                @error('state.hero_stats_years') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Students Enrolled</label>
                <input type="text" wire:model="state.hero_stats_students" class="form-input @error('state.hero_stats_students') is-invalid @enderror">
                @error('state.hero_stats_students') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Expert Teachers</label>
                <input type="text" wire:model="state.hero_stats_teachers" class="form-input @error('state.hero_stats_teachers') is-invalid @enderror">
                @error('state.hero_stats_teachers') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Pass Rate</label>
                <input type="text" wire:model="state.hero_stats_pass_rate" class="form-input @error('state.hero_stats_pass_rate') is-invalid @enderror">
                @error('state.hero_stats_pass_rate') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Save Hero Section</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
