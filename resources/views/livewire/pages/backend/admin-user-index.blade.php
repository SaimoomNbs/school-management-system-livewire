<div>
    
    {{-- ── Page header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">User Management</h1>
            <p class="page-desc">Manage system users, their roles and access status.</p>
        </div>
        <button wire:click="openCreate" class="btn-primary" id="btn-add-user">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add User
        </button>
    </div>

    {{-- ── Main Card ── --}}
    <div class="card">
        {{-- Toolbar --}}
        <div class="tbl-toolbar">
            {{-- Search --}}
            <div class="tbl-search-wrap">
                <svg class="tbl-search-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="search" 
                    class="tbl-search-input" 
                    placeholder="Search..."
                    x-data
                    x-init="setTimeout(() => $el.value = '', 200)"
                    autocomplete="off"
                >
            </div>

            {{-- Role Filter --}}
            <select wire:model.live="filterRole" class="tbl-filter-select" id="user-filter-role">
                <option value="">All Roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ str($r->name)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select wire:model.live="filterStatus" class="tbl-filter-select" id="user-filter-status">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <span class="tbl-count">{{ $users->total() }} Users</span>
        </div>

        {{-- Table --}}
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="width:110px" class="tbl-actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="tbl-muted">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                            <td><span class="tbl-bold">{{ $user->name }}</span></td>
                            <td class="tbl-muted">{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-accent">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button wire:click="toggleStatus({{ $user->id }})" class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }}" style="cursor:pointer; border:none;" title="Click to toggle status">
                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td>
                                <div class="tbl-actions">
                                    <button wire:click="openEdit({{ $user->id }})" class="tbl-btn tbl-btn-edit" title="Edit User">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="tbl-btn tbl-btn-delete" title="Delete User">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="tbl-empty">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="color:var(--color-text-4);margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <p>No users found matching your query.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- ── User Form Modal ── --}}
    <div x-data="{ open: @entangle('showModal') }" x-show="open" x-cloak class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="modal-box" x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeModal()" @keydown.escape.window="$wire.closeModal()">
            
            <div class="modal-head">
                <div>
                    <h3 class="modal-title">{{ $userId ? 'Edit User' : 'Add New User' }}</h3>
                    <p class="modal-sub">{{ $userId ? 'Update user account details.' : 'Create a new system user.' }}</p>
                </div>
                <button wire:click="closeModal" class="modal-close">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="modal-body">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="form-required">*</span></label>
                        <input type="text" wire:model="name" class="form-input @error('name') is-invalid @enderror" placeholder="e.g. John Doe">
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address <span class="form-required">*</span></label>
                        <input type="email" wire:model="email" class="form-input @error('email') is-invalid @enderror" placeholder="user@example.com">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Role <span class="form-required">*</span></label>
                            <select wire:model="role" class="form-input @error('role') is-invalid @enderror">
                                <option value="">Select Role</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ str($r->name)->replace('_', ' ')->title() }}</option>
                                @endforeach
                            </select>
                            @error('role') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select wire:model="status" class="form-input">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password @if(!$userId) <span class="form-required">*</span> @endif</label>
                        <input type="password" wire:model="password" class="form-input @error('password') is-invalid @enderror" placeholder="{{ $userId ? 'Leave blank to keep current' : 'Enter strong password' }}">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="modal-foot">
                <button wire:click="closeModal" class="btn-outline">Cancel</button>
                <button wire:click="save" wire:loading.attr="disabled" class="btn-primary" id="btn-save-user">
                    <span wire:loading.remove wire:target="save">{{ $userId ? 'Update User' : 'Create User' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Delete Modal ── --}}
    <div x-data="{ open: @entangle('showDeleteModal') }" x-show="open" x-cloak class="modal-backdrop"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="modal-box modal-box--sm" x-show="open"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.outside="$wire.closeDeleteModal()" @keydown.escape.window="$wire.closeDeleteModal()">
            
            <div class="modal-body" style="text-align:center; padding-top:28px;">
                <div class="modal-danger-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="modal-title" style="margin-top:14px;">Delete User?</h3>
                <p class="modal-sub" style="margin-top:6px;">This action is permanent and will remove the user's access.</p>
            </div>

            <div class="modal-foot" style="justify-content:center; gap:10px;">
                <button wire:click="closeDeleteModal" class="btn-outline">Keep it</button>
                <button wire:click="delete" wire:loading.attr="disabled" class="btn-danger" id="btn-confirm-delete-user">
                    <span wire:loading.remove wire:target="delete">Yes, Delete</span>
                    <span wire:loading wire:target="delete">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
</div>
