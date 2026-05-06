<!-- ============================================================ -->
<!-- 7. CONTACT SECTION -->
<!-- ============================================================ -->
<section id="contact" class="py-24 bg-white relative overflow-hidden">
    <div class="absolute right-0 bottom-0 w-64 h-64 bg-yellow-400/10 rounded-full translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 text-teal-700 font-semibold text-sm mb-4">
                {!! $badge !!}
            </div>
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-gray-900 leading-tight">
                {!! $title !!}
            </h2>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-start">
            <!-- Left: Info + Map -->
            <div class="reveal">
                <!-- Contact Details -->
                <div class="space-y-5 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 min-w-[48px] bg-yellow-400 rounded-xl flex items-center justify-center text-xl">📍</div>
                        <div>
                            <p class="font-semibold text-gray-900">ঠিকানা</p>
                            <p class="text-gray-500 text-sm">{{ $address }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 min-w-[48px] bg-orange-100 rounded-xl flex items-center justify-center text-xl">📞</div>
                        <div>
                            <p class="font-semibold text-gray-900">মোবাইল নম্বর</p>
                            <p class="text-gray-500 text-sm">{!! nl2br(e($phone_setting)) !!}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 min-w-[48px] bg-yellow-100 rounded-xl flex items-center justify-center text-xl">✉️</div>
                        <div>
                            <p class="font-semibold text-gray-900">ইমেইল</p>
                            <p class="text-gray-500 text-sm">{!! nl2br(e($email_setting)) !!}</p>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                @if($google_map_iframe)
                    <div class="rounded-2xl h-64 overflow-hidden shadow-lg border border-gray-100">
                        {!! $google_map_iframe !!}
                    </div>
                @else
                    <!-- Fallback placeholder if no iframe set -->
                    <div class="map-placeholder rounded-2xl h-52 flex items-center justify-center relative overflow-hidden shadow-lg">
                        <div class="absolute inset-0 opacity-20" style="background-image: repeating-linear-gradient(0deg,transparent,transparent 40px,rgba(255,255,255,.1) 40px,rgba(255,255,255,.1) 41px),repeating-linear-gradient(90deg,transparent,transparent 40px,rgba(255,255,255,.1) 40px,rgba(255,255,255,.1) 41px)"></div>
                        <div class="text-center z-10">
                            <div class="text-5xl mb-2">🗺️</div>
                            <p class="text-white font-semibold text-sm">গুগল ম্যাপ</p>
                            <p class="text-teal-200 text-xs">{{ $address }}</p>
                        </div>
                        <!-- Pin -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <div class="w-5 h-5 bg-yellow-400 rounded-full border-4 border-white shadow-lg animate-ping absolute"></div>
                            <div class="w-5 h-5 bg-yellow-400 rounded-full border-4 border-white shadow-lg relative z-10"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Form -->
            <div class="bg-gray-950 rounded-3xl p-8 reveal">
                <h3 class="font-display font-bold text-white text-xl mb-6">আমাদের যোগাযোগ করুন</h3>
                
                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-teal-500/20 border border-teal-500/50 rounded-xl text-teal-400 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="submit" class="space-y-4">
                    <div>
                        <label class="text-gray-400 text-xs font-medium mb-1 block">আপনার নাম</label>
                        <input type="text" wire:model="name" placeholder="আপনার পূর্ণ নাম লিখুন" class="w-full bg-white/10 border border-white/10 text-white placeholder-gray-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-yellow-400 transition-colors"/>
                        @error('name') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-400 text-xs font-medium mb-1 block">মোবাইল নম্বর</label>
                        <input type="tel" wire:model="phone" placeholder="+৮৮০ ১XXXXXXXXX" class="w-full bg-white/10 border border-white/10 text-white placeholder-gray-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-yellow-400 transition-colors"/>
                        @error('phone') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-gray-400 text-xs font-medium mb-1 block">মেসেজ লিখুন</label>
                        <textarea wire:model="message" rows="5" placeholder="আমরা কীভাবে সহায়তা করতে পারি জানান..." class="w-full bg-white/10 border border-white/10 text-white placeholder-gray-500 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-yellow-400 transition-colors resize-none"></textarea>
                        @error('message') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold py-3.5 rounded-xl transition-colors duration-200 font-display flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submit">পাঠিয়ে দিন →</span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>