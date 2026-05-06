<div>

    {{-- ── Page header ── --}}
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.teachers.index') }}" wire:navigate class="btn-outline" style="padding:7px 10px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="page-title">{{ $editingId ? 'Edit Teacher' : 'Add New Teacher' }}</h1>
                <p class="page-desc">
                    {{ $editingId ? 'Update teacher profile and account.' : 'Fill in details to create a teacher account.' }}
                </p>
            </div>
        </div>
    </div>

    <form wire:submit="save" enctype="multipart/form-data" novalidate>
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

            {{-- ── LEFT: Main form card ─────────────────────────── --}}
            <div class="card" style="padding:24px;">

                {{-- Photo upload --}}
                <div class="form-section-title">Photo</div>
                <div class="photo-upload-area" x-data="{ dragging: false }" @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false" @drop.prevent="dragging = false"
                    :class="{ 'is-dragging': dragging }">

                    {{-- Current / preview --}}
                    <div class="photo-preview-wrap">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="photo-preview-img" alt="Preview">
                        @elseif ($existingPhoto)
                            <img src="{{ asset('storage/' . $existingPhoto) }}" class="photo-preview-img" alt="Photo">
                        @else
                            <div class="photo-placeholder">
                                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div style="flex:1;">
                        <label for="photo-input" class="photo-upload-label">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            {{ $photo ? 'Change photo' : ($existingPhoto ? 'Replace photo' : 'Upload photo') }}
                        </label>
                        <input type="file" id="photo-input" wire:model="photo" accept="image/*" class="sr-only">
                        <p style="font-size:12px;color:var(--color-text-3);margin-top:4px;">
                            JPG, PNG or WebP. Max 2MB.
                        </p>
                        @if ($existingPhoto && !$photo)
                            <button type="button" wire:click="removePhoto"
                                style="margin-top:6px;font-size:12px;color:var(--color-danger);cursor:pointer;text-decoration:underline;background:none;border:none;">
                                Remove photo
                            </button>
                        @endif
                    </div>

                    <div wire:loading wire:target="photo" class="photo-uploading">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="animation:spin 0.8s linear infinite">
                            <path
                                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                        </svg>
                        Uploading…
                    </div>
                </div>
                @error('photo') <p class="form-error" style="margin-top:6px;">{{ $message }}</p> @enderror

                <div class="divider"></div>

                {{-- Personal info --}}
                <div class="form-section-title">Personal Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="t-name" class="form-label">Full Name <span class="form-required">*</span></label>
                        <input type="text" id="t-name" wire:model="name"
                            class="form-input @error('name') is-invalid @enderror" placeholder="e.g. John Doe">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="t-email" class="form-label">Email Address <span
                                class="form-required">*</span></label>
                        <input type="email" id="t-email" wire:model="email"
                            class="form-input @error('email') is-invalid @enderror" placeholder="teacher@academy.com">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="t-phone" class="form-label">Phone</label>
                        <input type="text" id="t-phone" wire:model="phone"
                            class="form-input @error('phone') is-invalid @enderror" placeholder="+880 1700 000000">
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="t-dob" class="form-label">Date of Birth</label>
                        <input type="date" id="t-dob" wire:model="dob"
                            class="form-input @error('dob') is-invalid @enderror">
                        @error('dob') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="t-address" class="form-label">Address</label>
                    <textarea id="t-address" wire:model="address" rows="2"
                        class="form-input @error('address') is-invalid @enderror" placeholder="Full address…"
                        style="resize:vertical;"></textarea>
                    @error('address') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="divider"></div>

                {{-- Professional info --}}
                <div class="form-section-title">Professional Details</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="t-joining" class="form-label">Joining Date <span
                                class="form-required">*</span></label>
                        <input type="date" id="t-joining" wire:model="joining_date"
                            class="form-input @error('joining_date') is-invalid @enderror">
                        @error('joining_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="t-qual" class="form-label">Qualification</label>
                        <input type="text" id="t-qual" wire:model="qualification"
                            class="form-input @error('qualification') is-invalid @enderror"
                            placeholder="e.g. M.Sc. Mathematics">
                        @error('qualification') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Status card ───────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Status card --}}
                <div class="card" style="padding:20px;">
                    <div class="form-section-title" style="margin-bottom:14px;">Account Status</div>

                    <div class="status-toggle-wrap">
                        <div>
                            <p style="font-size:13.5px;font-weight:600;color:var(--color-text-1);">
                                {{ $status ? 'Active' : 'Inactive' }}
                            </p>
                            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">
                                {{ $status ? 'Teacher can log in.' : 'Teacher cannot log in.' }}
                            </p>
                        </div>
                        <button type="button" wire:click="$set('status', {{ $status ? 0 : 1 }})"
                            class="status-toggle {{ $status ? 'is-active' : '' }}" id="btn-toggle-status">
                            <span class="status-toggle-knob"></span>
                        </button>
                    </div>

                    @if (!$editingId)
                        <div class="form-info-box" style="margin-top:16px;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            A login account will be auto-created. The generated password will appear in the success message.
                        </div>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div class="card" style="padding:16px;display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary"
                        style="width:100%;justify-content:center;display:flex" id="btn-save-teacher">
                        <span wire:loading.remove wire:target="save" style="display: inline-flex;align-items: center;gap: 5px;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $editingId ? 'Update Teacher' : 'Create Teacher' }}
                        </span>
                        <span wire:loading wire:target="save">Saving…</span>
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" wire:navigate class="btn-outline"
                        style="width:100%;justify-content:center;text-align:center;">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>