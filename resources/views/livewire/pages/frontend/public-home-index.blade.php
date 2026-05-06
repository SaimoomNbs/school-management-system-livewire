<div>
    {{-- Navigation Header --}}
    <livewire:layout.public-header />

    <!-- ============================================================ -->
    <!-- 1. HERO SECTION -->
    <!-- ============================================================ -->
    <section class="relative min-h-screen bg-white pt-24 pb-16 overflow-hidden" id="hero">
        <!-- Background shapes -->
        <div class="absolute top-10 right-0 w-[520px] h-[520px] bg-yellow-400 rounded-[60%_40%_70%_30%/_50%_60%_40%_60%] opacity-10 -z-0"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-600 rounded-full opacity-10 -translate-x-1/2 translate-y-1/2 -z-0"></div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10">
            <!-- Left -->
            <div>
                @if($hero_notice)
                <div class="inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
                    {{ $hero_notice }}
                </div>
                @endif
                <h1 class="font-display text-5xl md:text-6xl font-extrabold leading-tight text-gray-900 mb-4">
                    {!! $hero_title !!}
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed mb-8 max-w-md">
                    {{ $hero_subtitle }}
                </p>
            <!-- Buttons -->
                <div class="flex flex-wrap gap-4 mb-10">
                @if($hero_btn1_text)
                <a href="{{ $hero_btn1_link }}" wire:navigate class="bg-orange-600 hover:bg-orange-800 text-white font-semibold px-7 py-3.5 rounded-full flex items-center gap-2 transition-all duration-200 shadow-lg shadow-teal-700/20">
                    {{ $hero_btn1_text }}
                </a>
                @endif
                @if($hero_btn2_text)
                <a href="{{ $hero_btn2_link }}" wire:navigate class="border-2 border-gray-900 text-gray-900 font-semibold px-7 py-3.5 rounded-full hover:bg-gray-900 hover:text-white transition-all duration-200">
                    {{ $hero_btn2_text }}
                </a>
                @endif
            </div>
            <!-- Social proof -->
            <div class="flex items-center gap-3">
                <div class="flex -space-x-3">
                <div class="w-10 h-10 rounded-full bg-orange-200 border-2 border-white flex items-center justify-center text-teal-800 font-bold text-xs">AK</div>
                <div class="w-10 h-10 rounded-full bg-yellow-200 border-2 border-white flex items-center justify-center text-yellow-800 font-bold text-xs">NR</div>
                <div class="w-10 h-10 rounded-full bg-pink-200 border-2 border-white flex items-center justify-center text-pink-800 font-bold text-xs">SM</div>
                </div>
                <div>
                <div class="flex text-yellow-400 text-sm">★★★★★</div>
                <p class="text-gray-500 text-xs mt-0.5"><strong class="text-gray-800">{{ $hero_stats_students }}+</strong> সফল ও সন্তুষ্ট শিক্ষার্থী</p>
                </div>
            </div>
            </div>

            <!-- Right: Image card stack -->
            <div class="relative flex justify-center items-center">
            <!-- Yellow blob behind -->
            <div class="absolute w-80 h-80 bg-yellow-400 rounded-[60%_40%_70%_30%/_50%_60%_40%_60%] opacity-30 top-0 right-0"></div>
            <!-- Teal card -->
            <div class="relative z-10 bg-orange-600 rounded-3xl p-6 w-72 shadow-2xl mr-8 mt-8">
                <img src="https://scontent.fdac165-1.fna.fbcdn.net/v/t39.30808-6/495317044_1206321724621960_56619461942344493_n.jpg?stp=c144.0.864.864a_dst-jpg_s206x206_tt6&_nc_cat=111&ccb=1-7&_nc_sid=5df8b4&_nc_eui2=AeHqh3mx2asFppfQykZHcOh_GwFsdVPXTw0bAWx1U9dPDQR3yd-WXjX7IVzSkIE6XbRjezABZ0SXhqmib6NGtju7&_nc_ohc=CPR5dTDamWQQ7kNvwGdrgcJ&_nc_oc=AdquY8Bt3P7HhRSRfeGvHxJNuwb3VgtlBI_46KcwY9PpkDiOKOG_L39hEd6C1T6aFMc&_nc_zt=23&_nc_ht=scontent.fdac165-1.fna&_nc_gid=la29O7Psezfx8tlHdy079A&_nc_ss=7b2a8&oh=00_Af6QKVx_HeKRN1jfIy2m8UiINW8WBylGJjcOxSyzm1_qZw&oe=69FBD2AA" class="w-full h-full object-fill rounded-xl" alt="">
            </div>
            <!-- White card floating -->
            <div class="absolute bottom-0 left-0 bg-white rounded-2xl p-4 shadow-xl z-20 w-48">
                <div class="text-3xl mb-1">📚</div>
                <p class="font-display font-bold text-gray-900 text-sm">মোঃ রিয়াজ মাহামুদ সুজন</p>
                <p class="text-gray-400 text-xs">চিত্রাঙ্কন ও বর্ণাঙ্কন শিল্পী</p>
            </div>
            <!-- Asterisk decorations -->
            <span class="absolute top-4 left-4 text-yellow-400 text-3xl font-bold z-30">✦</span>
            <span class="absolute bottom-12 right-4 text-teal-300 text-2xl font-bold z-30">✦</span>
            </div>
        </div>

        <!-- Stats bar -->
        <div class="max-w-7xl mx-auto px-6 mt-16 relative z-10">
            <div class="bg-gray-900 rounded-2xl p-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <p class="font-display font-extrabold text-3xl text-yellow-400">{{ $hero_stats_years }}</p>
                <p class="text-gray-400 text-sm mt-1">সাফল্যের বছর</p>
            </div>
            <div>
                <p class="font-display font-extrabold text-3xl text-yellow-400">{{ $hero_stats_students }}</p>
                <p class="text-gray-400 text-sm mt-1">শিক্ষার্থী ভর্তি</p>
            </div>
            <div>
                <p class="font-display font-extrabold text-3xl text-yellow-400">{{ $hero_stats_teachers }}</p>
                <p class="text-gray-400 text-sm mt-1">অভিজ্ঞ শিক্ষক</p>
            </div>
            <div>
                <p class="font-display font-extrabold text-3xl text-yellow-400">{{ $hero_stats_pass_rate }}</p>
                <p class="text-gray-400 text-sm mt-1">পাসের হার</p>
            </div>
            </div>
        </div>
    </section>



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
    <!-- 3. WHY CHOOSE US -->
    <!-- ============================================================ -->
    <section id="why-us" class="py-24 bg-gray-950 relative overflow-hidden">
        <!-- Big yellow arc -->
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full border-4 border-yellow-400/20"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-orange-600/10"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <!-- Header -->
            <div class="text-center mb-16 reveal">
            <h2 class="font-display font-extrabold text-4xl md:text-5xl text-white leading-tight mb-4">
                {!! $why_title !!}
            </h2>
            @if($why_badge)
            <div class="inline-flex items-center gap-2 text-yellow-400 font-semibold text-sm">{{ $why_badge }}</div>
            @endif
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($feature_cards as $card)
            <!-- Card -->
            <div class="rounded-2xl overflow-hidden reveal group cursor-pointer">
                <div class="relative h-64 bg-gradient-to-br from-teal-700 to-teal-900 flex items-center justify-center overflow-hidden">
                @if($card->image_path)
                <img src="{{ asset('storage/' . $card->image_path) }}" alt="{{ $card->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500"/>
                @else
                <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=600&q=80" alt="{{ $card->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500"/>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 z-10">
                    @if($card->icon)
                    <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center text-xl mb-3">{{ $card->icon }}</div>
                    @endif
                    <h3 class="font-display font-bold text-white text-xl mb-2">{{ $card->title }}</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $card->description }}</p>
                </div>
                </div>
            </div>
            @empty
            <p class="text-gray-400">No features added yet. Add them from the Admin Panel.</p>
            @endforelse
            </div>
        </div>
    </section>

    {{-- Events Section --}}
    <livewire:frontend.public-event-index />
    
    <!-- ============================================================ -->
    <!-- BUILD YOUR LEGACY BANNER (inspired by image) -->
    <!-- ============================================================ -->
    <section class="py-0 bg-white">
        <div class="max-w-7xl mx-auto px-6">
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

    {{-- Gallery Section --}}
    <livewire:frontend.public-gallery-index />

    {{-- Testimonials Section --}}
    <livewire:frontend.public-home-testimonials />

    {{-- Contact Section --}}
    <livewire:frontend.public-home-contact />

    {{-- Footer --}}
    <livewire:layout.public-footer />
</div>
