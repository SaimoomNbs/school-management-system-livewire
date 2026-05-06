<div>

    {{-- Navigation Header --}}
    <livewire:layout.public-header />
    <!-- ============================================================ -->
    <!-- GALLERY -->
    <!-- ============================================================ -->
    <section id="gallery" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-10 reveal">
                @if($gallery_badge)
                <div class="inline-flex items-center gap-2 text-teal-700 font-semibold text-sm mb-4">{{ $gallery_badge }}</div>
                @endif
                <h2 class="font-display font-extrabold text-4xl md:text-5xl text-gray-900 leading-tight">
                    {!! $gallery_title !!}
                </h2>
                <p class="text-gray-500 mt-4 max-w-xl mx-auto">{{ $gallery_desc }}</p>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mb-10 justify-center reveal">
                <button wire:click="$set('filterCategory', '')" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ $filterCategory === '' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">সকল ছবি</button>
                @foreach ($categories as $cat)
                    <button wire:click="$set('filterCategory', '{{ $cat }}')" class="px-5 py-2 rounded-full text-sm font-semibold transition-all {{ $filterCategory === $cat ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $cat }}</button>
                @endforeach
            </div>

            <!-- Gallery grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @forelse ($galleries as $gal)
                    <div class="gallery-item rounded-2xl overflow-hidden {{ $loop->first ? 'row-span-2 h-80 md:h-auto' : 'h-40 md:h-48' }} bg-gray-900 flex items-center justify-center relative shadow-md group">
                        @if ($gal->image)
                            <img src="{{ asset('storage/' . $gal->image) }}" alt="{{ $gal->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent z-10 opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-4 left-4 right-4 z-20 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <p class="text-white font-display font-bold {{ $loop->first ? 'text-lg' : 'text-sm' }} truncate">{{ $gal->title }}</p>
                            <p class="text-teal-200 text-xs mt-1 uppercase tracking-wider font-semibold">{{ $gal->category }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 md:col-span-3 text-center py-16 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-gray-500 text-lg font-semibold">No images matched this specific category.</p>
                    </div>
                @endforelse
            </div>
            
            @if(method_exists($galleries, 'hasPages') && $galleries->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $galleries->links() }}
                </div>
            @endif
        </div>
    </section>


    {{-- Footer --}}
    <livewire:layout.public-footer />
</div>
