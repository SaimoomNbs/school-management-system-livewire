<!-- ============================================================ -->
<!-- 6. TESTIMONIALS / RESULTS -->
<!-- ============================================================ -->
<section id="testimonials" class="py-24 bg-gray-950 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400/5 rounded-full translate-x-1/2 -translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-14 reveal">
        <div class="inline-flex items-center gap-2 text-yellow-400 font-semibold text-sm mb-4">{{ $testimonial_badge }}</div>
        <h2 class="font-display font-extrabold text-4xl md:text-5xl text-white leading-tight">
            {!! $testimonial_title !!}
        </h2>
        </div>

        <!-- Testimonial cards -->
        <div class="grid md:grid-cols-3 gap-6">
        @forelse($testimonials as $testi)
        <div class="border-l-4 border-yellow-400 bg-white/5 rounded-2xl p-7 reveal">
            <div class="flex text-yellow-400 text-lg mb-4">
                {{ str_repeat('★', $testi->rating) }}
            </div>
            <p class="text-gray-300 text-sm leading-relaxed mb-6">"{{ $testi->quote }}"</p>
            <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-200 flex items-center justify-center text-teal-800 font-bold text-sm">
                {{ strtoupper(substr($testi->name, 0, 1)) . (strpos($testi->name, ' ') !== false ? strtoupper(substr(strrchr($testi->name, " "), 1, 1)) : '') }}
            </div>
            <div>
                <p class="text-white font-semibold text-sm">{{ $testi->name }}</p>
                @if($testi->subtitle)
                    <p class="text-gray-500 text-xs">{{ $testi->subtitle }}</p>
                @endif
            </div>
            </div>
        </div>
        @empty
            <div class="col-span-3 text-center py-10">
                <p class="text-gray-400 italic">No testimonials added yet. Add them from the Admin Panel.</p>
            </div>
        @endforelse
        </div>
    </div>
</section>