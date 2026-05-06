<?php

namespace App\Livewire\Frontend;

use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class EventPage extends Component
{
    use WithPagination;

    public function render()
    {
        $events = DB::table('events')
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        return view('livewire.pages.frontend.event-page', [
            'events' => $events,
            'event_badge' => Setting::get('event_badge', '✦ All Events'),
            'event_title' => Setting::get('event_title', 'Upcoming & Past Events<span class="dot text-yellow-400">.</span>'),
            'event_desc'  => Setting::get('event_desc', 'Discover our extracurricular activities and institutional milestones.'),
        ])->layout('layouts.guest');
    }
}
