<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\EventResource;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        try {
            $event = Event::with('categories:name', 'venue')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json(new EventResource($event));
    }


    /**
     * Store and validate a newly created event
     * 
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $event = new Event($validatedData);
        
        $event->save();

        $event->categories()->attach($validatedData['category_ids']);

        return response()->json(new EventResource($event), 201);
    }

    /**
     * Update the specified event
     * 
     * @param UpdateEventRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateEventRequest $request, $id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $validatedData = $request->validated();

        $event->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start' => $validatedData['start'],
            'end' => $validatedData['end'],
            'venue_id' => $validatedData['venue_id'],
        ]);

        $event->categories()->sync($validatedData['category_ids']);

        return response()->json(new EventResource($event));
    }


    /**
     * Delete the specified event
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Event not found'], 404);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Failed to delete event'], 500);
        }
    }
}
