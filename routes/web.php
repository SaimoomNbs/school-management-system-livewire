<?php

use App\Livewire\Backend\AdminAttendanceMarkSheet;
use App\Livewire\Backend\AdminAttendanceReport;
use App\Livewire\Backend\AdminClassIndex;
use App\Livewire\Backend\AdminExamGroupIndex;
use App\Livewire\Backend\AdminExamIndex;
use App\Livewire\Backend\AdminEventIndex;
use App\Livewire\Backend\AdminFeeIndex;
use App\Livewire\Backend\AdminGalleryIndex;
use App\Livewire\Backend\AdminGenerateMonthlyFees;
use App\Livewire\Backend\AdminInvoiceCreate;
use App\Livewire\Backend\AdminPageIndex;
use App\Livewire\Backend\AdminPaymentRecord;
use App\Livewire\Backend\AdminResultEntry;
use App\Livewire\Backend\AdminResultReport;
use App\Livewire\Backend\AdminSectionIndex;
use App\Livewire\Backend\AdminAboutSection;
use App\Livewire\Backend\AdminWhyUsSection;
use App\Livewire\Backend\AdminTestimonialIndex;
use App\Livewire\Backend\AdminHeroSection;
use App\Livewire\Backend\AdminSettingsForm;
use App\Livewire\Backend\AdminStudentForm;
use App\Livewire\Backend\AdminStudentIndex;
use App\Livewire\Backend\AdminStudentShow;
use App\Livewire\Backend\AdminSubjectIndex;
use App\Livewire\Backend\AdminTeacherForm;
use App\Livewire\Backend\AdminTeacherIndex;
use App\Livewire\Backend\AdminTeacherShow;
use App\Livewire\Backend\SharedNotificationList;
use App\Livewire\Backend\StudentMyAttendance;
use App\Livewire\Backend\StudentMyResults;
use App\Livewire\Backend\AdminContactIndex;
use App\Livewire\Frontend\PublicEventIndex;
use App\Livewire\Frontend\PublicGalleryIndex;
use App\Livewire\Frontend\PublicPageView;
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\PublicHomeIndex;
use App\Livewire\Backend\DashboardComponent;
use App\Livewire\Frontend\AboutPage;
use App\Livewire\Frontend\ContactPage;
use App\Livewire\Frontend\EventPage;
use App\Livewire\Frontend\GalleryPage;


Route::get('/', PublicHomeIndex::class)->name('home');
Route::get('/events', EventPage::class)->name('all.events');
Route::get('/gallery', GalleryPage::class)->name('all.gallery');
Route::get('/about-us', AboutPage::class)->name('about.us');
Route::get('/contact-us', ContactPage::class)->name('contact.us');
// Route::get('page/{slug}', PublicPageView::class)->name('public.page');


/* ---------------------------------------------------------- */
/*  Admin – Academic Management                               */
/* ---------------------------------------------------------- */
Route::get('dashboard', DashboardComponent::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Classes
    Route::get('classes', AdminClassIndex::class)->name('classes.index');

    // Sections
    Route::get('sections', AdminSectionIndex::class)->name('sections.index');

    // Subjects
    Route::get('subjects', AdminSubjectIndex::class)->name('subjects.index');
    Route::get('subjects/my', \App\Livewire\Backend\StudentMySubjects::class)->name('subjects.my');

    // Teachers — create MUST be before {teacher} wildcard
    Route::get('teachers',                AdminTeacherIndex::class)->name('teachers.index');
    Route::get('teachers/create',         AdminTeacherForm::class)->name('teachers.create');
    Route::get('teachers/{teacher}/edit', AdminTeacherForm::class)->name('teachers.edit');
    Route::get('teachers/{teacher}',      AdminTeacherShow::class)->name('teachers.show');

    // Students — create MUST be before {student} wildcard
    Route::get('students',                AdminStudentIndex::class)->name('students.index');
    Route::get('students/create',         AdminStudentForm::class)->name('students.create');
    Route::get('students/{student}/edit', AdminStudentForm::class)->name('students.edit');
    Route::get('students/{student}',      AdminStudentShow::class)->name('students.show');

    // Attendance — static segments first
    Route::get('attendance',         AdminAttendanceMarkSheet::class)->name('attendance.mark-sheet');
    Route::get('attendance/report',  AdminAttendanceReport::class)->name('attendance.report');
    Route::get('attendance/my',      StudentMyAttendance::class)->name('attendance.my');

    // Fees & Finance
    Route::get('fees',                          AdminFeeIndex::class)->name('fees.index');
    Route::get('fees/generate-monthly',         AdminGenerateMonthlyFees::class)->name('fees.generate-monthly');
    Route::get('fees/my',                       \App\Livewire\Backend\StudentMyFees::class)->name('fees.my');
    Route::get('invoices/create',               AdminInvoiceCreate::class)->name('invoices.create');
    Route::get('payments/create',               AdminPaymentRecord::class)->name('payments.create');

    // Examinations
    Route::get('exam-groups',                   AdminExamGroupIndex::class)->name('exam-groups.index');
    Route::get('exams',                         AdminExamIndex::class)->name('exams.index');
    Route::get('results/entry',                 AdminResultEntry::class)->name('results.entry');
    Route::get('results/report',                AdminResultReport::class)->name('results.report');
    Route::get('results/my',                    StudentMyResults::class)->name('results.my');

    // System Tools & Content
    Route::get('users',                         \App\Livewire\Backend\AdminUserIndex::class)->name('users.index');
    Route::get('notifications',                 SharedNotificationList::class)->name('notifications.index');
    Route::get('events',                        AdminEventIndex::class)->name('events.index');
    Route::get('gallery',                       AdminGalleryIndex::class)->name('gallery.index');
    Route::get('pages',                         AdminPageIndex::class)->name('pages.index');
    Route::get('settings',                      AdminSettingsForm::class)->name('settings.index');
    Route::get('hero-section',                  AdminHeroSection::class)->name('hero-section');
    Route::get('about-section',                 AdminAboutSection::class)->name('about-section');
    Route::get('why-us-section',                AdminWhyUsSection::class)->name('why-us-section');
    Route::get('testimonial-section',           AdminTestimonialIndex::class)->name('testimonial-section');
    Route::get('contacts',                      AdminContactIndex::class)->name('contacts.index');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', PublicPageView::class)->name('pages.show');
