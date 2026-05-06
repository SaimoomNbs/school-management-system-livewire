<!-- ============================================================ -->
<!-- 8. FOOTER -->
<!-- ============================================================ -->
<footer class="bg-gray-950 border-t border-white/10 pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid md:grid-cols-4 gap-10 mb-12">
      <!-- Brand -->
      <div class="md:col-span-2">
        <div class="flex items-center gap-2 mb-4">
          @if(\App\Models\Setting::get('logo_path'))
            <img src="{{ asset('storage/' . \App\Models\Setting::get('logo_path')) }}" alt="Logo" class="h-9">
          @else
            <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
          @endif
          <!-- <span class="font-display font-extrabold text-xl text-white">{{ strtoupper(\App\Models\Setting::get('academy_name', 'Suzon Care Academy')) }}<span class="text-yellow-400">.</span></span> -->
        </div>
        <p class="text-gray-500 text-sm leading-relaxed max-w-xs mb-6">
          {{ \App\Models\Setting::get('footer_description', 'অংকন ও সুন্দর হাতের লেখা প্রশিক্ষণ কেন্দ্র,রংপুর') }}
        </p>
        <!-- Social links -->
        <div class="flex gap-4">
          {{-- Facebook --}}
          @if($fb = \App\Models\Setting::get('fb_link'))
          <a href="{{ $fb }}" target="_blank" 
             class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:border-blue-600 hover:text-white hover:-translate-y-1 transition-all duration-300 group shadow-lg"
             title="Follow us on Facebook">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
          </a>
          @endif
          
          {{-- YouTube --}}
          @if($yt = \App\Models\Setting::get('youtube_link'))
          <a href="{{ $yt }}" target="_blank" 
             class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:bg-red-600 hover:border-red-600 hover:text-white hover:-translate-y-1 transition-all duration-300 group shadow-lg"
             title="Subscribe on YouTube">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
            </svg>
          </a>
          @endif
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <p class="font-display font-bold text-white mb-4 text-sm">প্রয়োজনীয় লিঙ্ক</p>
        <ul class="space-y-3 text-gray-500 text-sm">
          <li><a href="{{ route('home') }}" wire:navigate class="over:text-yellow-400 transition-colors">হোম</a></li>
          <li><a href="{{ route('about.us') }}" wire:navigate class="over:text-yellow-400 transition-colors">পরিচিতি</a></li>
          <li><a href="{{ route('all.events') }}" wire:navigate class="over:text-yellow-400 transition-colors">কার্যক্রম</a></li>
          <li><a href="{{ route('all.gallery') }}" wire:navigate class="over:text-yellow-400 transition-colors">ছবি গ্যালারি</a></li>
          <li><a href="{{ route('contact.us') }}" wire:navigate class="over:text-yellow-400 transition-colors">যোগাযোগ করুন</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <p class="font-display font-bold text-white mb-4 text-sm">যোগাযোগ করুন</p>
        <ul class="space-y-3 text-gray-500 text-sm">
          <li class="flex gap-2 items-start"><span class="text-yellow-400">📍</span>{{ \App\Models\Setting::get('address', 'Keranipara, Rangpur-1254') }}</li>
          <li class="flex gap-2 items-center"><span class="text-yellow-400">📞</span>{{ \App\Models\Setting::get('phone', '+880 1800-BRIGHT') }}</li>
          <li class="flex gap-2 items-center"><span class="text-yellow-400">✉️</span>{{ \App\Models\Setting::get('email', 'info@Suzon Care Academy.edu.bd') }}</li>
          <!-- <li class="flex gap-2 items-center"><span class="text-yellow-400">🕐</span>Sun–Thu: 8am – 4pm</li> -->
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-600">
      <p>© {{ date('Y') }} {{ \App\Models\Setting::get('academy_name', 'Suzon Care Academy') }} সকল স্বত্ব সংরক্ষিত।</p>
      <div class="flex gap-6">
        <a href="{{ url('/privacy-policy') }}" wire:navigate class="hover:text-gray-400 transition-colors">গোপনীয়তা নীতি</a>
        <a href="{{ url('/terms') }}" wire:navigate class="hover:text-gray-400 transition-colors">ব্যবহারের শর্তাবলী</a>
        <!-- <a href="#" class="hover:text-gray-400 transition-colors">Sitemap</a> -->
      </div>
    </div>
  </div>
</footer>