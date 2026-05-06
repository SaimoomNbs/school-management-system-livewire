<?php

namespace App\Livewire\Backend;

use App\Models\ClassModel;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fee;
use App\Models\Payment;

class AdminStudentForm extends Component
{
    use WithFileUploads;

    // ── State ────────────────────────────────────────────────
    public ?int    $editingId     = null;
    public ?int    $userId        = null;
    public ?string $existingPhoto = null;

    // ── Form fields ─────────────────────────────────────────
    public string $name             = '';
    public string $email            = '';
    public string $phone            = '';
    public string $guardian_name    = '';
    public string $guardian_phone   = '';
    public string $guardian_address = '';
    public string $dob              = '';
    public string $admission_date   = '';
    public string $class_id         = '';
    public string $section_id       = '';
    public string $monthly_fee      = '';
    public string $admission_fee    = '';
    public int    $status           = 1;

    // ── Photo upload ─────────────────────────────────────────
    public $photo = null;

    // ── Mount ────────────────────────────────────────────────
    public function mount(Student $student = null): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        if ($student && $student->exists) {
            $this->editingId        = $student->id;
            $this->userId           = $student->user_id;
            $this->existingPhoto    = $student->photo;
            $this->name             = $student->name             ?? '';
            $this->email            = $student->email            ?? '';
            $this->phone            = $student->phone            ?? '';
            $this->guardian_name    = $student->guardian_name    ?? '';
            $this->guardian_phone   = $student->guardian_phone   ?? '';
            $this->guardian_address = $student->guardian_address ?? '';
            $this->dob              = $student->dob              ? $student->dob->format('Y-m-d')             : '';
            $this->admission_date   = $student->admission_date   ? $student->admission_date->format('Y-m-d')  : '';
            $this->class_id         = (string) ($student->class_id   ?? '');
            $this->section_id       = (string) ($student->section_id ?? '');
            $this->monthly_fee      = (string) ($student->monthly_fee ?? '');
            $this->status           = $student->status ? 1 : 0;
        } else {
            $this->admission_date = now()->format('Y-m-d');
        }
    }

    // ── Dependent dropdown: class → sections ─────────────────
    public function updatedClassId(): void
    {
        $this->section_id = '';   // sections re-computed in render()
    }

    // ── Validation rules ─────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['nullable', 'email', 'max:255',
                                   Rule::unique('users', 'email')->ignore($this->userId)],
            'phone'            => ['nullable', 'string', 'max:20'],
            'guardian_name'    => ['nullable', 'string', 'max:255'],
            'guardian_phone'   => ['nullable', 'string', 'max:20'],
            'guardian_address' => ['nullable', 'string', 'max:500'],
            'dob'              => ['nullable', 'date', 'before:today'],
            'admission_date'   => ['required', 'date'],
            'class_id'         => ['nullable', 'exists:classes,id'],
            'section_id'       => ['nullable', 'exists:sections,id'],
            'monthly_fee'      => ['nullable', 'numeric', 'min:0'],
            'admission_fee'    => ['nullable', 'numeric', 'min:0'],
            'status'           => ['required', 'boolean'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $allClasses  = ClassModel::orderBy('name')->get();
        $allSections = $this->class_id
            ? Section::where('class_id', $this->class_id)->orderBy('name')->get()
            : collect();

        return view('livewire.pages.backend.admin-student-form',
            compact('allClasses', 'allSections')
        )->layout('layouts.app');
    }

    // ── Save ─────────────────────────────────────────────────
    public function save(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);
        $this->validate();

        // Handle photo
        $photoPath = $this->existingPhoto;
        if ($this->photo) {
            if ($this->existingPhoto) {
                Storage::disk('public')->delete($this->existingPhoto);
            }
            $photoPath = $this->photo->store('students', 'public');
        }

        DB::transaction(function () use ($photoPath) {

            if ($this->editingId) {
                /* ── UPDATE ── */
                $student = Student::findOrFail($this->editingId);

                $student->update([
                    'name'             => $this->name,
                    'email'            => $this->email            ?: null,
                    'phone'            => $this->phone            ?: null,
                    'guardian_name'    => $this->guardian_name    ?: null,
                    'guardian_phone'   => $this->guardian_phone   ?: null,
                    'guardian_address' => $this->guardian_address ?: null,
                    'dob'              => $this->dob              ?: null,
                    'admission_date'   => $this->admission_date,
                    'class_id'         => $this->class_id         ?: null,
                    'section_id'       => $this->section_id       ?: null,
                    'monthly_fee'      => $this->monthly_fee      ?: 0,
                    'status'           => $this->status,
                    'photo'            => $photoPath,
                ]);

                // Sync linked user
                if ($student->user_id) {
                    User::find($student->user_id)?->update([
                        'name'   => $this->name,
                        'email'  => $this->email  ?: null,
                        'phone'  => $this->phone  ?: null,
                        'status' => $this->status,
                    ]);
                }

                session()->flash('success', 'Student updated successfully.');

            } else {
                /* ── CREATE ── */
                $nextNum   = (Student::max('id') ?? 0) + 1;
                $studentId = 'STD-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                // $password  = Str::random(10);
                $password  = '12345678';

                // Use provided email or generate a placeholder
                $email = $this->email
                    ?: Str::slug($this->name, '.') . '.' . $nextNum . '@student.local';

                $user = User::create([
                    'name'     => $this->name,
                    'email'    => $email,
                    'phone'    => $this->phone ?: null,
                    'password' => Hash::make($password),
                    'status'   => $this->status,
                ]);
                $user->assignRole('student');

                $student = Student::create([
                    'user_id'          => $user->id,
                    'student_id'       => $studentId,
                    'name'             => $this->name,
                    'email'            => $this->email            ?: null,
                    'phone'            => $this->phone            ?: null,
                    'guardian_name'    => $this->guardian_name    ?: null,
                    'guardian_phone'   => $this->guardian_phone   ?: null,
                    'guardian_address' => $this->guardian_address ?: null,
                    'dob'              => $this->dob              ?: null,
                    'admission_date'   => $this->admission_date,
                    'class_id'         => $this->class_id         ?: null,
                    'section_id'       => $this->section_id       ?: null,
                    'monthly_fee'      => $this->monthly_fee      ?: 0,
                    'status'           => $this->status,
                    'photo'            => $photoPath,
                ]);

                if ($this->admission_fee > 0) {
                    $fee = Fee::create([
                        'student_id' => $student->id,
                        'type'       => 'Admission',
                        'amount'     => $this->admission_fee,
                        'month'      => now()->month,
                        'year'       => now()->year,
                        'label'      => 'Admission Fee',
                        'due_date'   => now(),
                        'status'     => 'paid',
                        'created_by' => auth()->id(),
                    ]);

                    Payment::create([
                        'student_id'       => $student->id,
                        'fee_id'           => $fee->id,
                        'amount'           => $this->admission_fee,
                        'payment_method'   => 'Cash',
                        'transaction_note' => 'Paid at admission',
                        'paid_at'          => now(),
                        'collected_by'     => auth()->id(),
                    ]);
                }

                session()->flash('success',
                    "Student created! ID: {$studentId} | Login: {$email} | Password: {$password}"
                );
            }
        });

        $this->redirect(route('admin.students.index'), navigate: true);
    }

    // ── Remove existing photo ────────────────────────────────
    public function removePhoto(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'accountant']), 403);

        if ($this->existingPhoto) {
            Storage::disk('public')->delete($this->existingPhoto);
            Student::where('id', $this->editingId)->update(['photo' => null]);
            $this->existingPhoto = null;
        }
        $this->photo = null;
    }
}
