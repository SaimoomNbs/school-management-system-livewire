<?php

namespace App\Livewire\Backend;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminTeacherForm extends Component
{
    use WithFileUploads;

    // ── State ────────────────────────────────────────────────
    public ?int    $editingId     = null;
    public ?int    $userId        = null;
    public ?string $existingPhoto = null;

    // ── Form fields ─────────────────────────────────────────
    public string $name          = '';
    public string $email         = '';
    public string $phone         = '';
    public string $address       = '';
    public string $dob           = '';
    public string $joining_date  = '';
    public string $qualification = '';
    public int    $status        = 1;

    // ── File upload ──────────────────────────────────────────
    public $photo = null;

    // ── Mount: called for both create & edit routes ──────────
    public function mount(Teacher $teacher = null): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);

        if ($teacher && $teacher->exists) {
            $this->editingId     = $teacher->id;
            $this->userId        = $teacher->user_id;
            $this->existingPhoto = $teacher->photo;
            $this->name          = $teacher->name          ?? '';
            $this->email         = $teacher->email         ?? '';
            $this->phone         = $teacher->phone         ?? '';
            $this->address       = $teacher->address       ?? '';
            $this->dob           = $teacher->dob           ? $teacher->dob->format('Y-m-d')          : '';
            $this->joining_date  = $teacher->joining_date  ? $teacher->joining_date->format('Y-m-d') : '';
            $this->qualification = $teacher->qualification ?? '';
            $this->status        = $teacher->status        ? 1 : 0;
        } else {
            $this->joining_date = now()->format('Y-m-d');
        }
    }

    // ── Validation rules ─────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255',
                                Rule::unique('users', 'email')->ignore($this->userId)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string', 'max:500'],
            'dob'           => ['nullable', 'date', 'before:today'],
            'joining_date'  => ['required', 'date'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'status'        => ['required', 'boolean'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        return view('livewire.pages.backend.admin-teacher-form')
            ->layout('layouts.app');
    }

    // ── Save (create or update) ──────────────────────────────
    public function save(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);
        $this->validate();

        // Handle photo upload
        $photoPath = $this->existingPhoto;
        if ($this->photo) {
            // Remove old photo when updating
            if ($this->existingPhoto) {
                Storage::disk('public')->delete($this->existingPhoto);
            }
            $photoPath = $this->photo->store('teachers', 'public');
        }

        DB::transaction(function () use ($photoPath) {

            if ($this->editingId) {
                /* ── UPDATE ── */
                $teacher = Teacher::findOrFail($this->editingId);

                $teacher->update([
                    'name'          => $this->name,
                    'email'         => $this->email,
                    'phone'         => $this->phone         ?: null,
                    'address'       => $this->address       ?: null,
                    'dob'           => $this->dob           ?: null,
                    'joining_date'  => $this->joining_date,
                    'qualification' => $this->qualification ?: null,
                    'status'        => $this->status,
                    'photo'         => $photoPath,
                ]);

                // Keep linked user in sync
                if ($teacher->user_id) {
                    User::find($teacher->user_id)?->update([
                        'name'   => $this->name,
                        'email'  => $this->email,
                        'phone'  => $this->phone ?: null,
                        'status' => $this->status,
                    ]);
                }

                session()->flash('success', 'Teacher profile updated successfully.');

            } else {
                /* ── CREATE ── */

                // Auto-generate teacher_id: TCH-0001
                $nextNum   = (Teacher::max('id') ?? 0) + 1;
                $teacherId = 'TCH-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                // Random password — shown once in flash
                // $password = Str::random(10);
                $password = '12345678';

                // Create user account
                $user = User::create([
                    'name'     => $this->name,
                    'email'    => $this->email,
                    'phone'    => $this->phone ?: null,
                    'password' => Hash::make($password),
                    'status'   => $this->status,
                ]);

                $user->assignRole('teacher');

                // Create teacher record
                Teacher::create([
                    'user_id'       => $user->id,
                    'teacher_id'    => $teacherId,
                    'name'          => $this->name,
                    'email'         => $this->email,
                    'phone'         => $this->phone         ?: null,
                    'address'       => $this->address       ?: null,
                    'dob'           => $this->dob           ?: null,
                    'joining_date'  => $this->joining_date,
                    'qualification' => $this->qualification ?: null,
                    'status'        => $this->status,
                    'photo'         => $photoPath,
                ]);

                session()->flash('success',
                    "Teacher created! ID: {$teacherId} | Login: {$this->email} | Password: {$password}"
                );
            }
        });

        $this->redirect(route('admin.teachers.index'), navigate: true);
    }

    // ── Remove existing photo ─────────────────────────────────
    public function removePhoto(): void
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 403);

        if ($this->existingPhoto) {
            Storage::disk('public')->delete($this->existingPhoto);
            Teacher::where('id', $this->editingId)->update(['photo' => null]);
            $this->existingPhoto = null;
        }

        $this->photo = null;
    }
}
