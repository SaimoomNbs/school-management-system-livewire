<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
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
            <h1 class="auth-title">স্বাগতম</h1>
            <p class="auth-sub">আপনার ড্যাশবোর্ডে প্রবেশের জন্য সঠিক তথ্য প্রদান করুন।</p>

            <form wire:submit="login">
                
                @if (session('status'))
                    <div class="flash-alert flash-success" style="margin-bottom: 20px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="email" class="form-label">ইমেইল</label>
                    <input wire:model="form.email" id="email" type="email" class="form-input @error('form.email') is-invalid @enderror" placeholder="আপনার ইমেইল লিখুন" required autofocus autocomplete="username" style="padding: 10px 14px; font-size: 14px;">
                    @error('form.email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <label for="password" class="form-label" style="margin-bottom:0;">পাসওয়ার্ড</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate style="font-size:12px; color:var(--color-accent); font-weight:600; text-decoration:none;">পাসওয়ার্ড ভুলে গেছেন?</a>
                        @endif
                    </div>
                    <input wire:model="form.password" id="password" type="password" class="form-input @error('form.password') is-invalid @enderror" placeholder="••••••••" required autocomplete="current-password" style="padding: 10px 14px; font-size: 14px; letter-spacing: 2px;">
                    @error('form.password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; align-items:center; margin-bottom: 28px;">
                    <input wire:model="form.remember" id="remember" type="checkbox" style="accent-color:var(--color-accent); width:15px; height:15px; cursor:pointer; margin-right:8px; border-radius:4px;">
                    <label for="remember" style="font-size:13px; color:var(--color-text-2); cursor:pointer; user-select:none;">৩০ দিনের জন্য মনে রাখুন</label>
                </div>

                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:11px; font-size:14px; background:var(--color-accent); border-radius:var(--r-sm);">
                    <span wire:loading.remove wire:target="login">প্রবেশ করুন</span>
                    <span wire:loading wire:target="login">যাচাই করা হচ্ছে...</span>
                </button>
            </form>
        </div>
    </div>
</div>
