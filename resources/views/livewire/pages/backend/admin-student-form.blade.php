<div>
    
    {{-- ── Back + Page header ── --}}
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('admin.students.index') }}" wire:navigate class="btn-outline" style="padding:7px 10px;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-title">{{ $editingId ? 'Edit Student' : 'Enroll New Student' }}</h1>
                <p class="page-desc">{{ $editingId ? 'Update student profile and account.' : 'Fill in details to enroll a new student.' }}</p>
            </div>
        </div>
    </div>

    <form wire:submit="save" novalidate>
        <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

            {{-- ── LEFT: Main form ─────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Photo card --}}
                <div class="card" style="padding:20px;">
                    <div class="form-section-title">Student Photo</div>
                    <div class="photo-upload-area" x-data="{ dragging: false }"
                        @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false"
                        :class="{ 'is-dragging': dragging }">
                        <div class="photo-preview-wrap">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="photo-preview-img" alt="Preview">
                            @elseif ($existingPhoto)
                                <img src="{{ asset('storage/' . $existingPhoto) }}" class="photo-preview-img" alt="Photo">
                            @else
                                <div class="photo-placeholder">
                                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <label for="photo-input" class="photo-upload-label">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                {{ $photo ? 'Change' : ($existingPhoto ? 'Replace' : 'Upload Photo') }}
                            </label>
                            <input type="file" id="photo-input" wire:model="photo" accept="image/*" class="sr-only">
                            <p style="font-size:12px;color:var(--color-text-3);margin-top:4px;">JPG, PNG, WebP — max 2MB</p>
                            @if ($existingPhoto && !$photo)
                                <button type="button" wire:click="removePhoto" style="margin-top:6px;font-size:12px;color:var(--color-danger);cursor:pointer;text-decoration:underline;background:none;border:none;">Remove photo</button>
                            @endif
                        </div>
                        <div wire:loading wire:target="photo" class="photo-uploading">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 0.8s linear infinite"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            Uploading…
                        </div>
                    </div>
                    @error('photo') <p class="form-error" style="margin-top:6px;">{{ $message }}</p> @enderror
                </div>

                {{-- Personal info --}}
                <div class="card" style="padding:20px;">
                    <div class="form-section-title">Personal Information</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="s-name" class="form-label">Full Name <span class="form-required">*</span></label>
                            <input type="text" id="s-name" wire:model="name"
                                class="form-input @error('name') is-invalid @enderror" placeholder="Student's full name">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="s-email" class="form-label">Email <span class="form-optional">(optional)</span></label>
                            <input type="email" id="s-email" wire:model="email"
                                class="form-input @error('email') is-invalid @enderror" placeholder="student@example.com">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="s-phone" class="form-label">Phone</label>
                            <input type="text" id="s-phone" wire:model="phone"
                                class="form-input @error('phone') is-invalid @enderror" placeholder="+880…">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="s-dob" class="form-label">Date of Birth</label>
                            <input type="date" id="s-dob" wire:model="dob"
                                class="form-input @error('dob') is-invalid @enderror">
                            @error('dob') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Academic info --}}
                <div class="card" style="padding:20px;">
                    <div class="form-section-title">Academic Assignment</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="s-admission" class="form-label">Admission Date <span class="form-required">*</span></label>
                            <input type="date" id="s-admission" wire:model="admission_date"
                                class="form-input @error('admission_date') is-invalid @enderror">
                            @error('admission_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="s-fee" class="form-label">Monthly Fee (৳)</label>
                            <input type="number" id="s-fee" wire:model="monthly_fee" min="0" step="0.01"
                                class="form-input @error('monthly_fee') is-invalid @enderror" placeholder="0.00">
                            @error('monthly_fee') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if (!$editingId)
                        <div class="form-row">
                            <div class="form-group">
                                <label for="s-admission-fee" class="form-label">Admission Fee (৳) <span class="form-optional">(One-time, Paid)</span></label>
                                <input type="number" id="s-admission-fee" wire:model="admission_fee" min="0" step="0.01"
                                    class="form-input @error('admission_fee') is-invalid @enderror" placeholder="0.00">
                                @error('admission_fee') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group"></div>
                        </div>
                    @endif

                    <div class="form-row">
                        {{-- Class → triggers section reload --}}
                        <div class="form-group">
                            <label for="s-class" class="form-label">Class</label>
                            <select id="s-class" wire:model.live="class_id"
                                class="form-input @error('class_id') is-invalid @enderror">
                                <option value="">— Select Class —</option>
                                @foreach ($allClasses as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Section (dependent on class) --}}
                        <div class="form-group">
                            <label for="s-section" class="form-label">Section</label>
                            <div style="position:relative;">
                                <select id="s-section" wire:model="section_id"
                                    class="form-input @error('section_id') is-invalid @enderror"
                                    @if (!$class_id) disabled @endif>
                                    <option value="">{{ $class_id ? '— Select Section —' : '— Select Class first —' }}</option>
                                    @foreach ($allSections as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="class_id"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 0.8s linear infinite;color:var(--color-text-3)"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                </div>
                            </div>
                            @error('section_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Guardian info --}}
                <div class="card" style="padding:20px;">
                    <div class="form-section-title">Guardian / Parent Information</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="s-gname" class="form-label">Guardian Name</label>
                            <input type="text" id="s-gname" wire:model="guardian_name"
                                class="form-input @error('guardian_name') is-invalid @enderror" placeholder="Father / Mother / Guardian">
                            @error('guardian_name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="s-gphone" class="form-label">Guardian Phone</label>
                            <input type="text" id="s-gphone" wire:model="guardian_phone"
                                class="form-input @error('guardian_phone') is-invalid @enderror" placeholder="+880…">
                            @error('guardian_phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="s-gaddr" class="form-label">Guardian Address</label>
                        <textarea id="s-gaddr" wire:model="guardian_address" rows="2"
                            class="form-input @error('guardian_address') is-invalid @enderror"
                            placeholder="Full address…" style="resize:vertical;"></textarea>
                        @error('guardian_address') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Status + Actions ─────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div class="card" style="padding:20px;">
                    <div class="form-section-title" style="margin-bottom:14px;">Account Status</div>
                    <div class="status-toggle-wrap">
                        <div>
                            <p style="font-size:13.5px;font-weight:600;color:var(--color-text-1);">{{ $status ? 'Active' : 'Inactive' }}</p>
                            <p style="font-size:12px;color:var(--color-text-3);margin-top:2px;">{{ $status ? 'Student can log in.' : 'Student cannot log in.' }}</p>
                        </div>
                        <button type="button" wire:click="$set('status', {{ $status ? 0 : 1 }})"
                            class="status-toggle {{ $status ? 'is-active' : '' }}" id="btn-toggle-student-status">
                            <span class="status-toggle-knob"></span>
                        </button>
                    </div>
                    @if (!$editingId)
                        <div class="form-info-box" style="margin-top:16px;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            A login account is auto-created. Credentials appear in the success message.
                        </div>
                    @endif
                </div>

                <div class="card" style="padding:16px;display:flex;flex-direction:column;gap:10px;">
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary"
                        style="width:100%;justify-content:center;" id="btn-save-student">
                        <span wire:loading.remove wire:target="save" style="display:flex;align-items:center;gap:6px;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $editingId ? 'Update Student' : 'Enroll Student' }}
                        </span>
                        <span wire:loading wire:target="save">Saving…</span>
                    </button>
                    <a href="{{ route('admin.students.index') }}" wire:navigate
                        class="btn-outline" style="width:100%;justify-content:center;text-align:center;">Cancel</a>
                </div>
            </div>

        </div>
    </form>
</div>
