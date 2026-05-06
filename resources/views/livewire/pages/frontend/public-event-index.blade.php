<!-- ============================================================ -->
<!-- EVENTS / NEWS -->
<!-- ============================================================ -->
<section id="events" class="py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2 bg-yellow-400"></div>

    <div class="max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-14 reveal">
            <div>
                @if($event_badge)
                <div class="inline-flex items-center gap-2 text-teal-700 font-semibold text-sm mb-4">{{ $event_badge }}</div>
                @endif
                <h2 class="font-display font-extrabold text-4xl md:text-5xl text-gray-900 leading-tight">
                    {!! $event_title !!}
                </h2>
                <p class="text-gray-500 mt-4 text-lg">{{ $event_desc }}</p>
            </div>
        </div>

        <!-- Cards -->
        <div class="grid md:grid-cols-3 gap-8">
            @forelse ($events as $ev)
                <!-- Event Card -->
                <div class="event-card rounded-2xl overflow-hidden border border-gray-100 shadow-sm reveal transition-transform duration-200 hover:-translate-y-1">
                    @if ($ev->image)
                        <img src="{{ asset('storage/' . $ev->image) }}" alt="{{ $ev->title }}" class="w-full h-52 object-cover border-b border-gray-100">
                    @else
                        <div class="h-52 bg-orange-600 flex items-center justify-center relative overflow-hidden border-b border-gray-100">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-600 to-teal-900"></div>
                            <div class="relative z-10 text-white opacity-50">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                    @endif
                    <div class="p-6">
                        <p class="text-teal-700 text-xs font-semibold mb-2">{{ \Carbon\Carbon::parse($ev->event_date)->format('F d, Y') }}</p>
                        <h3 class="font-display font-bold text-xl text-gray-900 mb-2">{{ $ev->title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ Str::limit($ev->description, 120) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-gray-500 text-lg font-semibold">No events are currently marked publicly.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>
