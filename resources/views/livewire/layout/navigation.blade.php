<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<header class="topbar" x-data="{ profileOpen: false, searchFocused: false }">

    {{-- ── Hamburger (Mobile) ── --}}
    <button class="hamburger-btn" @click="sidebarOpen = true" aria-label="Open Sidebar">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    {{-- ── Search ── --}}
    <div class="topbar-search" :class="{ 'is-focused': searchFocused }">
        <i class="icon-search topbar-search-icon"></i>
        <input
            type="text"
            id="globalSearch"
            placeholder="Search anything…"
            autocomplete="off"
            @focus="searchFocused = true"
            @blur="searchFocused = false"
        >
        <div class="search-kbds">
            <kbd>⌘</kbd><kbd>K</kbd>
        </div>
    </div>

    {{-- ── Right cluster ── --}}
    <div class="topbar-right">

        {{-- Notifications ── --}}
        @livewire('backend.shared-notification-bell')

        {{-- Profile ── --}}
        <div class="tb-profile" @click.outside="profileOpen = false">

            <button
                class="tb-profile-btn"
                id="profileBtn"
                @click="profileOpen = !profileOpen"
                :aria-expanded="profileOpen"
            >
                <div class="tb-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="tb-user-info">
                    <span class="tb-user-name" x-data="{{ json_encode(['name' => auth()->user()->name ?? 'Admin']) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                    <span class="tb-user-role">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</span>
                </div>
                <i class="icon-chevron-down tb-chevron" :class="{ 'is-open': profileOpen }"></i>
            </button>

            {{-- Dropdown panel --}}
            <div
                class="profile-panel"
                id="profileDropdown"
                x-show="profileOpen"
                x-cloak
                x-transition:enter="panel-enter"
                x-transition:enter-start="panel-enter-from"
                x-transition:enter-end="panel-enter-to"
                x-transition:leave="panel-leave"
                x-transition:leave-start="panel-leave-from"
                x-transition:leave-end="panel-leave-to"
            >
                {{-- Panel header --}}
                <div class="panel-head">
                    <div class="panel-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="panel-head-text">
                        <p class="panel-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="panel-email">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>

                <div class="panel-rule"></div>

                <a href="{{ route('profile') }}" wire:navigate class="panel-item">
                    <i class="icon-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="#" class="panel-item">
                    <i class="icon-cog"></i>
                    <span>Account Settings</span>
                </a>
                <a href="#" class="panel-item">
                    <i class="icon-shield"></i>
                    <span>Security</span>
                </a>

                <div class="panel-rule"></div>

                <button wire:click="logout" class="panel-item panel-item--logout">
                    <i class="icon-sign-out"></i>
                    <span>Sign Out</span>
                </button>

            </div>

        </div>

    </div>

</header>
