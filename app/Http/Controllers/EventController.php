<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * A function that returns all events and their associated categories
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = Event::with('categories:name', 'venue')->get();

        $transformedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start,
                'end' => $event->end,
                'venue' => [
                    'name' => $event->venue->name,
                    'address' => $event->venue->address,
                ],
                'categories' => $event->categories->pluck('name')->toArray()
            ];
        });

        return response()->json($transformedEvents);
    }

    /**
     * A function that returns a specific event and its associated categories
     * 
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $event = Event::with('categories:name', 'venue')->where('id', $id)->first();

        $transformEvent = function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start,
                'end' => $event->end,
                'venue' => [
                    'name' => $event->venue->name,
                    'address' => $event->venue->address,
                ],
                'categories' => $event->categories->pluck('name')->toArray()
            ];
        };

        return response()->json($transformEvent($event));
    }
}
