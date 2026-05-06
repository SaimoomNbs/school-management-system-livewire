<div>

    {{-- Navigation Header --}}
    <livewire:layout.public-header />
    
    <!-- ============================================================ -->
    <!-- 2. ABOUT / INTRODUCTION -->
    <!-- ============================================================ -->
    <section id="about" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-yellow-400/5"></div>
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <!-- Left visual -->
            <div class="relative">
            <div class="bg-orange-600 rounded-3xl h-96 flex items-center justify-center relative overflow-hidden shadow-2xl">
                @if($about_school_image_path)
                    <img src="{{ asset('storage/' . $about_school_image_path) }}" alt="{{ $about_school_name }}" class="absolute inset-0 w-full h-full object-fill">
                    <!-- <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-gray-900/10"></div> -->
                @else
                    <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-400 rounded-bl-full opacity-50"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-tr-full"></div>
                @endif
                
                <div class="text-center z-10 p-8">
                @if(!$about_school_image_path)
                    <div class="text-6xl mb-4">🏫</div>
                    <p class="text-white font-display font-bold text-xl">{{ $about_school_name }}</p>
                    <p class="text-teal-200 text-sm mt-2">{{ $about_school_established }}</p>
                @endif
                </div>
            </div>
            <!-- Floating badge -->
            <div class="absolute -bottom-6 -right-6 bg-yellow-400 rounded-2xl px-6 py-4 shadow-xl">
                <p class="font-display font-extrabold text-2xl text-gray-900">{{ $about_school_badge_value }}</p>
                <p class="text-gray-700 text-xs font-medium">{{ $about_school_badge_label }}</p>
            </div>
            </div>

            <!-- Right text -->
            <div class="reveal">
            @if($about_badge)
            <div class="inline-flex items-center gap-2 text-teal-700 font-semibold text-sm mb-4">{{ $about_badge }}</div>
            @endif
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-gray-900 leading-tight mb-6">
                {!! $about_title !!}
            </h2>
            <p class="text-gray-500 leading-relaxed mb-6">
                {{ $about_description }}
            </p>
            <!-- Mission / Vision -->
            <div class="space-y-4 mb-8">
                <div class="flex gap-4 items-start">
                <div class="w-10 h-10 min-w-[40px] bg-yellow-400 rounded-xl flex items-center justify-center text-lg">🎯</div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $about_mission_title }}</p>
                    <p class="text-gray-500 text-sm">{{ $about_mission_desc }}</p>
                </div>
                </div>
                <div class="flex gap-4 items-start">
                <div class="w-10 h-10 min-w-[40px] bg-orange-100 rounded-xl flex items-center justify-center text-lg">👁️</div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $about_vision_title }}</p>
                    <p class="text-gray-500 text-sm">{{ $about_vision_desc }}</p>
                </div>
                </div>
            </div>
            @if($about_btn_text)
            <a href="{{ $about_btn_link }}" wire:navigate class="inline-flex items-center gap-2 bg-orange-600 text-white font-semibold px-6 py-3 rounded-full hover:bg-orange-800 transition-colors">
                {{ $about_btn_text }}
            </a>
            @endif
            </div>
        </div>
    </section>

    
    <!-- ============================================================ -->
    <!-- BUILD YOUR LEGACY BANNER (inspired by image) -->
    <!-- ============================================================ -->
    <section class="py-0 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="bg-yellow-400 rounded-3xl overflow-hidden relative">
            <div class="absolute -left-10 bottom-0 w-64 h-64 bg-gray-900/10 rounded-full -translate-x-1/2 translate-y-1/2"></div>
            <div class="absolute right-8 top-4 text-gray-900/10 text-[120px] font-black select-none">✦</div>
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 p-10 md:p-14">
                <div class="text-8xl flex-shrink-0">👨‍🎓</div>
                <div class="flex-1">
                <h2 class="font-display font-extrabold text-3xl md:text-4xl text-gray-900 leading-tight mb-3">
                    আপনার সন্তানের উজ্জ্বল ভবিষ্যৎ গড়ে তুলুন<br/>মূল্যবোধের সাথে কোনো আপস না করেই।<span class="text-teal-700">.</span>
                </h2>
                <p class="text-gray-700 text-base leading-relaxed max-w-lg">
                    Suzon Care Academy-তে ভর্তি করান এবং দেখুন আপনার সন্তান কীভাবে আত্মবিশ্বাসী, সহানুভূতিশীল ও দক্ষ একজন মানুষ হিসেবে গড়ে উঠছে — পরিবারের আদর্শ ও মূল্যবোধ অটুট রেখেই।
                </p>
                </div>
                <a href="{{ route('contact.us') }}" wire:navigate class="flex-shrink-0 bg-gray-900 hover:bg-orange-600 text-white font-semibold px-8 py-4 rounded-full transition-colors duration-200 text-sm whitespace-nowrap">
                আজই ভর্তি হোন →
                </a>
            </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <livewire:layout.public-footer />
</div>