<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
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

        return response()->json(EventResource::collection($events));
    }

    /**
     * A function that returns a specific event and its associated categories
     * 
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $event = Event::with('categories:name', 'venue')->find($id);

        return response()->json(new EventResource($event));
    }
}
