<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Holiday;
use App\Models\Leave;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = CalendarEvent::orderBy('start_date')->get();

        return view('calendar.index', compact('events'));
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        $events = collect();

        CalendarEvent::when($start, fn ($q) => $q->where('start_date', '>=', $start))
            ->when($end, fn ($q) => $q->where('start_date', '<=', $end))
            ->get()
            ->each(fn ($e) => $events->push($this->toFullCalendar($e, $e->color)));

        Holiday::when($start, fn ($q) => $q->where('date_holiday', '>=', $start))
            ->when($end, fn ($q) => $q->where('date_holiday', '<=', $end))
            ->get()
            ->each(fn ($h) => $events->push([
                'title' => 'Holiday: ' . $h->name_holiday,
                'start' => $h->date_holiday,
                'end'   => $h->date_holiday,
                'color' => '#28a745',
                'type'  => 'holiday',
            ]));

        Leave::where('status', 'Approved')
            ->when($start, fn ($q) => $q->where('date_from', '>=', $start))
            ->when($end, fn ($q) => $q->where('date_from', '<=', $end))
            ->get()
            ->each(fn ($l) => $events->push([
                'title' => 'Leave: ' . $l->employee_name,
                'start' => $l->date_from,
                'end'   => $l->date_to,
                'color' => '#ffc107',
                'type'  => 'leave',
            ]));

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'color'       => 'nullable|string',
            'type'        => 'nullable|string',
        ]);

        CalendarEvent::create([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'color'       => $request->color ?? '#3f51b5',
            'type'        => $request->type ?? 'event',
            'created_by'  => session('user_id'),
        ]);

        flash()->success('Event added successfully :)');

        return redirect()->back();
    }

    protected function toFullCalendar(CalendarEvent $event, string $color): array
    {
        return [
            'id'          => $event->id,
            'title'       => $event->title,
            'description' => $event->description,
            'start'       => $event->start_date->format('Y-m-d'),
            'end'         => $event->end_date ? $event->end_date->format('Y-m-d') : $event->start_date->format('Y-m-d'),
            'color'       => $color,
            'type'        => $event->type,
        ];
    }
}
