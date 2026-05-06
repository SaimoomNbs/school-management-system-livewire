<div>
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.fees.index') }}" wire:navigate class="btn-outline" style="padding:7px 10px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-title">Bulk Generate Monthly Fees</h1>
                <p class="page-desc">Assess monthly fees for all active students in a specific class.</p>
            </div>
        </div>
    </div>

    <form wire:submit="generate" class="card" style="padding:30px;max-width:600px;">
        <div class="form-group">
            <label class="form-label">Select Class <span class="form-required">*</span></label>
            <select wire:model="class_id" class="form-input @error('class_id') is-invalid @enderror">
                <option value="">— Select Class —</option>
                <option value="all">All Classes</option>
                @foreach ($allClasses as $cls)
                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                @endforeach
            </select>
            @error('class_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Month <span class="form-required">*</span></label>
                <select wire:model="month" class="form-input @error('month') is-invalid @enderror">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
                @error('month') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Year <span class="form-required">*</span></label>
                <select wire:model="year" class="form-input @error('year') is-invalid @enderror">
                    @foreach ($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                @error('year') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom:24px;">
            <label class="form-label">Due Date <span class="form-required">*</span></label>
            <input type="date" wire:model="due_date" class="form-input @error('due_date') is-invalid @enderror">
            @error('due_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-info-box" style="margin-bottom:24px;border:1px solid #bbf7d0;background:#f0fdf4;color:#166534;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            This action creates standard monthly fee records using each active student's set `monthly_fee` rate. Duplicate generation is silently ignored based on month and year.
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;">
            <a href="{{ route('admin.fees.index') }}" wire:navigate class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="generate">Generate Fees</span>
                <span wire:loading wire:target="generate">Generating...</span>
            </button>
        </div>
    </form>
</div>