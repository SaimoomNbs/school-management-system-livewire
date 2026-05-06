<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div>
            <div class="auth-logo">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2">
                    @if(\App\Models\Setting::get('logo_path'))
                        <img src="{{ asset('storage/' . \App\Models\Setting::get('logo_path')) }}" alt="Logo" class="h-9">
                    @else
                        <div class="w-9 h-9 blob-teal flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        </div>
                    @endif
                    <!-- <span class="font-display font-800 text-xl tracking-tight text-gray-900">{{ strtoupper(\App\Models\Setting::get('academy_name', 'Academy')) }}<span class="text-yellow-400">.</span></span> -->
                </a>
            </div>
            <h1 class="auth-title">পাসওয়ার্ড রিসেট করুন</h1>
            <p class="auth-sub" style="margin-bottom: 24px; line-height: 1.5; padding: 0 10px;">
                পাসওয়ার্ড ভুলে গেছেন? কোনো সমস্যা নেই। আপনার ইমেইল ঠিকানাটি প্রদান করুন এবং আমরা আপনাকে পাসওয়ার্ড রিসেট করার একটি লিঙ্ক পাঠিয়ে দেব।
            </p>

            @if (session('status'))
                <div class="flash-alert flash-success" style="margin-bottom: 20px;">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="sendPasswordResetLink">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="email" class="form-label">ইমেইল ঠিকানা</label>
                    <input wire:model="email" id="email" type="email" class="form-input @error('email') is-invalid @enderror" placeholder="আপনার ইমেইল লিখুন" required autofocus style="padding: 10px 14px; font-size: 14px;">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:11px; font-size:14px; background:var(--color-text-1); border-radius:var(--r-sm);">
                    <span wire:loading.remove wire:target="sendPasswordResetLink">রিসেট লিঙ্ক পাঠান</span>
                    <span wire:loading wire:target="sendPasswordResetLink">পাঠানো হচ্ছে...</span>
                </button>

                <div style="text-align: center; margin-top: 18px;">
                    <a href="{{ route('login') }}" wire:navigate style="font-size: 13.5px; color: var(--color-text-3); text-decoration: none; transition: color 0.15s ease;" onmouseover="this.style.color='var(--color-text-1)'" onmouseout="this.style.color='var(--color-text-3)'">
                        পূর্বের পাতায় <span style="color: var(--color-accent); font-weight: 600;">ফিরে যান</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
