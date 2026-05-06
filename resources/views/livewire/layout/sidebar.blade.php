<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="sidebar" id="adminSidebar">

    {{-- ── Brand ── --}}
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" wire:navigate class="brand-link">
            <div class="brand-icon">
                <svg style="width:18px;height:18px;color:#fff;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                    </path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">{{ config('app.name', 'Academy') }}</span>
                <span class="brand-tagline">Admin Panel</span>
            </div>
        </a>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="sidebar-nav" id="sidebarNav">

        <p class="nav-label">Overview</p>

        <a href="{{ route('dashboard') }}" wire:navigate
            class="nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                fill="currentColor" viewBox="0 0 20 24">
                <path fill-rule="evenodd"
                    d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                    clip-rule="evenodd" />
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Academic Management --}}
        @if(auth()->user()?->hasAnyRole(['super_admin', 'student', 'accountant']))
            <p class="nav-label" style="margin-top:20px;">Academic</p>
        @endif

        @if(auth()->user()?->hasAnyRole(['super_admin']))
            <a href="{{ route('admin.classes.index') }}" wire:navigate
                class="nav-item {{ request()->routeIs('admin.classes.*') ? 'is-active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Classes</span>
            </a>

            <a href="{{ route('admin.sections.index') }}" wire:navigate
                class="nav-item {{ request()->routeIs('admin.sections.*') ? 'is-active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span>Sections</span>
            </a>

            <a href="{{ route('admin.subjects.index') }}" wire:navigate
                class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'is-active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Subjects</span>
            </a>

            <a href="{{ route('admin.teachers.index') }}" wire:navigate
                class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'is-active' : '' }}">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Teachers</span>
            </a>
        @endif

        @if(auth()->user()?->hasAnyRole(['student']))
        <a href="{{ route('admin.subjects.my') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.subjects.my') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>My Subjects</span>
        </a>
        @endif

        @if(auth()->user()?->hasAnyRole(['super_admin', 'accountant']))
        <a href="{{ route('admin.students.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.students.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            <span>Students</span>
        </a>
        @endif

        {{-- Attendance Management --}}
        @if(auth()->user()?->hasAnyRole(['super_admin', 'teacher', 'student']))
        <p class="nav-label" style="margin-top:20px;">Attendance</p>
        @endif

        @if(auth()->user()?->hasAnyRole(['super_admin', 'teacher']))
        <a href="{{ route('admin.attendance.mark-sheet') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.attendance.mark-sheet') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span>Attendance Sheet</span>
        </a>

        <a href="{{ route('admin.attendance.report') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.attendance.report') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Report</span>
        </a>
        @endif

        @if(auth()->user()?->hasAnyRole(['student']))
        <a href="{{ route('admin.attendance.my') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.attendance.my') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>My Attendance</span>
        </a>
        @endif

        {{-- Fees & Payment Management --}}
        @if(auth()->user()?->hasAnyRole(['super_admin', 'accountant', 'student']))
        <p class="nav-label" style="margin-top:20px;">Finance</p>
        @endif

        @if(auth()->user()?->hasAnyRole(['super_admin', 'accountant']))
        <a href="{{ route('admin.fees.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.fees.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Fees</span>
        </a>

        <a href="{{ route('admin.invoices.create') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.invoices.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Generate Invoice</span>
        </a>

        <a href="{{ route('admin.payments.create') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Payment</span>
        </a>
        @endif

        @if(auth()->user()?->hasAnyRole(['student']))
        <a href="{{ route('admin.fees.my') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.fees.my') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Payment & Fees</span>
        </a>
        @endif

        {{-- Examinations Management --}}
        @if(auth()->user()?->hasAnyRole(['super_admin', 'teacher', 'student']))
        <p class="nav-label" style="margin-top:20px;">Examinations</p>
        @endif

        @if(auth()->user()?->hasAnyRole(['super_admin', 'teacher']))
        <a href="{{ route('admin.exam-groups.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.exam-groups.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Exam Groups</span>
        </a>

        <a href="{{ route('admin.exams.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.exams.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Exams (Subjects)</span>
        </a>

        <a href="{{ route('admin.results.entry') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.results.entry') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Result Entry</span>
        </a>

        <a href="{{ route('admin.results.report') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.results.report') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Result Report</span>
        </a>
        @endif

        @if(auth()->user()?->hasAnyRole(['student']))
        <a href="{{ route('admin.results.my') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.results.my') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span>Exam & Results</span>
        </a>
        @endif

        {{-- System & Content --}}
        @if(auth()->user()?->hasAnyRole(['super_admin']))
        <p class="nav-label" style="margin-top:20px;">System</p>

        <a href="{{ route('admin.users.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <span>Users</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Settings</span>
        </a>

        <a href="{{ route('admin.pages.index') }}" wire:navigate
            class="nav-item {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>All Custom Pages</span>
        </a>

        <div x-data="{ open: true }"> <!-- keep true if you want open by default -->

            <!-- MAIN MENU -->
            <button @click="open = !open"
                class="nav-item w-full flex justify-between items-center"
                :class="open ? 'text-blue-600 bg-gray-50' : ''">

                <div class="flex items-center gap-2.5">
                    <!-- icon -->
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h16v16H4z" />
                    </svg>

                    <span>Homepage Section</span>
                </div>

                <svg class="transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"
                    width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- SUB MENU -->
            <div x-show="open" x-cloak x-transition
                class="sidebar-sub-nav pl-7 mt-2 flex flex-col gap-1">

                <!-- Hero -->
                <a href="{{ route('admin.hero-section') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.hero-section') ? 'is-active' : '' }}">
                    <span>Hero Section</span>
                </a>

                <!-- About -->
                <a href="{{ route('admin.about-section') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.about-section') ? 'is-active' : '' }}">
                    <span>About Section</span>
                </a>

                <!-- Why Us -->
                <a href="{{ route('admin.why-us-section') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.why-us-section') ? 'is-active' : '' }}">
                    <span>Why Choose Us</span>
                </a>

                <!-- Events -->
                <a href="{{ route('admin.events.index') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.events.*') ? 'is-active' : '' }}">
                    <span>Events Section</span>
                </a>

                <!-- Gallery -->
                <a href="{{ route('admin.gallery.index') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.gallery.*') ? 'is-active' : '' }}">
                    <span>Gallery Section</span>
                </a>

                <!-- Testimonials -->
                <a href="{{ route('admin.testimonial-section') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.testimonial-section') ? 'is-active' : '' }}">
                    <span>Testimonials Section</span>
                </a>

                <!-- Contacts -->
                <a href="{{ route('admin.contacts.index') }}" wire:navigate
                    class="nav-item text-sm {{ request()->routeIs('admin.contacts.*') ? 'is-active' : '' }}">
                    <span>Contact Section</span>
                </a>

            </div>
        </div>
        @endif

    </nav>

    {{-- ── User footer ── --}}
    <div class="sidebar-footer">

        <div class="sidebar-user-card">
            <div class="su-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="su-info">
                <p class="su-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="su-role text-orange-600">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</p>
            </div>
            <button wire:click="logout" class="su-logout" title="Sign out">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                </svg>
            </button>
        </div>

    </div>

</aside>
