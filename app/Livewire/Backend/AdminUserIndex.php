<?php

namespace App\Livewire\Backend;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class AdminUserIndex extends Component
{
    use WithPagination;

    // ── Filters ──────────────────────────────────────────────
    public string $search = '';
    public string $filterRole = '';
    public string $filterStatus = '';

    // ── Form Modal ───────────────────────────────────────────
    public bool $showModal = false;
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $role = '';
    public string $password = '';
    public bool $status = true;

    // ── Delete Modal ─────────────────────────────────────────
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    // ── Lifecycle ────────────────────────────────────────────
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterRole(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $roles = Role::where('name', '!=', 'super_admin')->get();
        $users = User::query()
            ->with('roles')
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'super_admin'))
            ->when($this->search, fn($q) => 
                $q->where(fn($subQ) =>
                    $subQ->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%')
                )
            )
            ->when($this->filterRole, fn($q) => 
                $q->whereHas('roles', fn($rq) => $rq->where('name', $this->filterRole))
            )
            ->when($this->filterStatus !== '', fn($q) => 
                $q->where('status', (bool) $this->filterStatus)
            )
            ->latest()
            ->paginate(10);
        return view('livewire.pages.backend.admin-user-index', compact('users', 'roles'))
            ->layout('layouts.app');
    }

    // ── Modal Actions ────────────────────────────────────────
    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetForm();
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = (bool) $user->status;
        $this->role = $user->roles->first()?->name ?? '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';
        $this->status = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'role' => 'required|string|exists:roles,name',
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
            'status' => 'boolean',
        ];

        $validated = $this->validate($rules);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
            ]);
            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
            $user->syncRoles($this->role);
            session()->flash('success', 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'status' => $this->status,
            ]);
            $user->assignRole($this->role);
            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
    }

    // ── Status Toggle ────────────────────────────────────────
    public function toggleStatus(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['status' => !$user->status]);
        session()->flash('success', 'User status updated.');
    }

    // ── Delete ───────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', "You cannot delete yourself.");
            return;
        }
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId && $this->deletingId !== auth()->id()) {
            User::destroy($this->deletingId);
            session()->flash('success', 'User deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }
}
