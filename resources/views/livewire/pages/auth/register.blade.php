<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
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
            <h1 class="auth-title">অ্যাকাউন্ট তৈরি করুন</h1>
            <p class="auth-sub">শুরু করতে আপনার তথ্য প্রদান করুন।</p>

            <form wire:submit="register">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="name" class="form-label">নাম</label>
                    <input wire:model="name" id="name" type="text" class="form-input @error('name') is-invalid @enderror" placeholder="আপনার পূর্ণ নাম লিখুন" required autofocus autocomplete="name" style="padding: 10px 14px; font-size: 14px;">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="email" class="form-label">ইমেইল</label>
                    <input wire:model="email" id="email" type="email" class="form-input @error('email') is-invalid @enderror" placeholder="আপনার ইমেইল লিখুন" required autocomplete="username" style="padding: 10px 14px; font-size: 14px;">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="password" class="form-label">পাসওয়ার্ড</label>
                    <input wire:model="password" id="password" type="password" class="form-input @error('password') is-invalid @enderror" placeholder="••••••••" required autocomplete="new-password" style="padding: 10px 14px; font-size: 14px; letter-spacing: 2px;">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-input @error('password_confirmation') is-invalid @enderror" placeholder="••••••••" required autocomplete="new-password" style="padding: 10px 14px; font-size: 14px; letter-spacing: 2px;">
                    @error('password_confirmation') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:11px; font-size:14px; background:var(--color-accent); border-radius:var(--r-sm);">
                    <span wire:loading.remove wire:target="register">নিবন্ধন করুন</span>
                    <span wire:loading wire:target="register">নিবন্ধন করা হচ্ছে...</span>
                </button>

                <div style="text-align: center; margin-top: 18px;">
                    <a href="{{ route('login') }}" wire:navigate style="font-size: 13.5px; color: var(--color-text-3); text-decoration: none; transition: color 0.15s ease;" onmouseover="this.style.color='var(--color-text-1)'" onmouseout="this.style.color='var(--color-text-3)'">
                        ইতিমধ্যে অ্যাকাউন্ট আছে? <span style="color: var(--color-accent); font-weight: 600;">প্রবেশ করুন</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
