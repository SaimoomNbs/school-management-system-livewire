<!-- ============================================================ -->
<!-- NAVBAR -->
<!-- ============================================================ -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
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
    <!-- Links -->
    <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700 list-none m-0 p-0">
      <li><a href="{{ route('home') }}" wire:navigate class="nav-link hover:text-gray-900">হোম</a></li>
      <li><a href="{{ route('about.us') }}" wire:navigate class="nav-link hover:text-gray-900">পরিচিতি</a></li>
      <li><a href="{{ route('all.events') }}" wire:navigate class="nav-link hover:text-gray-900">কার্যক্রম</a></li>
      <li><a href="{{ route('all.gallery') }}" wire:navigate class="nav-link hover:text-gray-900">ছবি গ্যালারি</a></li>
      <li><a href="{{ route('contact.us') }}" wire:navigate class="nav-link hover:text-gray-900">যোগাযোগ করুন</a></li>
    </ul>
    <!-- CTA -->
    <div class="flex items-center gap-3">
      <a href="{{ route('login') }}" wire:navigate class="bg-orange-600 hover:bg-orange-800 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-colors duration-200">লগইন →</a>
    </div>
  </div>
</nav>