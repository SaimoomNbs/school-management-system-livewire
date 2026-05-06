<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">General Settings</h1>
            <p class="page-desc">Modify institutional metadata securely globally deployed via configuration flags.</p>
        </div>
    </div>

    <form wire:submit="save" class="card" style="padding:32px;max-width:800px;">
        
        <div class="form-section-title">Institutional Brand</div>
        
        <div class="form-group">
            <label class="form-label">Academy Name <span class="form-required">*</span></label>
            <input type="text" wire:model="state.academy_name" class="form-input @error('state.academy_name') is-invalid @enderror">
            @error('state.academy_name') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Upload Brand Logo</label>
                <input type="file" wire:model="logo_file" class="form-input @error('logo_file') is-invalid @enderror" accept="image/*">
                @error('logo_file') <p class="form-error">{{ $message }}</p> @enderror
                @if (isset($state['logo_path']) && $state['logo_path'])
                    <div style="margin-top:8px;">
                        <img src="{{ asset('storage/' . $state['logo_path']) }}" style="height:40px; border-radius:4px;">
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Upload Favicon</label>
                <input type="file" wire:model="favicon_file" class="form-input @error('favicon_file') is-invalid @enderror" accept="image/*">
                @error('favicon_file') <p class="form-error">{{ $message }}</p> @enderror
                @if (isset($state['favicon_path']) && $state['favicon_path'])
                    <div style="margin-top:8px;">
                        <img src="{{ asset('storage/' . $state['favicon_path']) }}" style="height:32px; width:32px; border-radius:4px;">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-section-title" style="margin-top:32px;">Contact Defaults</div>

        <div class="form-group">
            <label class="form-label">Physical Address</label>
            <textarea wire:model="state.address" class="form-input @error('state.address') is-invalid @enderror" rows="2"></textarea>
            @error('state.address') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Support Phone</label>
                <input type="text" wire:model="state.phone" class="form-input @error('state.phone') is-invalid @enderror">
                @error('state.phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Corporate Email</label>
                <input type="email" wire:model="state.email" class="form-input @error('state.email') is-invalid @enderror">
                @error('state.email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-section-title" style="margin-top:32px;">Social & Meta</div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Facebook Page Link</label>
                <input type="url" wire:model="state.fb_link" class="form-input @error('state.fb_link') is-invalid @enderror" placeholder="https://facebook.com/...">
                @error('state.fb_link') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">YouTube Channel Link</label>
                <input type="url" wire:model="state.youtube_link" class="form-input @error('state.youtube_link') is-invalid @enderror" placeholder="https://youtube.com/...">
                @error('state.youtube_link') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom:24px;">
            <label class="form-label">Footer Short Description</label>
            <textarea wire:model="state.footer_description" class="form-input @error('state.footer_description') is-invalid @enderror" rows="3" placeholder="Briefly describe the academy for the footer..."></textarea>
            @error('state.footer_description') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group" style="margin-bottom:24px;">
            <label class="form-label">Google Map Iframe Code</label>
            <textarea wire:model="state.google_map_iframe" class="form-input @error('state.google_map_iframe') is-invalid @enderror" rows="4" placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'></textarea>
            <p style="font-size: 11px; color: var(--color-text-3); margin-top: 4px;">Paste the full <code>&lt;iframe&gt;</code> code from Google Maps share embed option.</p>
            @error('state.google_map_iframe') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-section-title" style="margin-top:32px;">Localization Target</div>

        <div class="form-group" style="margin-bottom:24px;max-width:200px;">
            <label class="form-label">Finance Currency Symbol <span class="form-required">*</span></label>
            <input type="text" wire:model="state.currency_symbol" class="form-input @error('state.currency_symbol') is-invalid @enderror" placeholder="৳ or $">
            @error('state.currency_symbol') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div style="padding-top:16px;border-top:1px solid var(--color-border-lgt);display:flex;justify-content:flex-end;">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Update Settings</span>
                <span wire:loading wire:target="save">Updating Configurations...</span>
            </button>
        </div>
    </form>
</div>
