<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class VenueController extends Controller
{

    /**
     * A function that returns all venues
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $venues = Venue::select('id', 'name', 'address')->get();

        return response()->json($venues);
    }

    /**
     * A function that returns a specific venue
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $venue = Venue::findorFail($id);
            $venue = Venue::select('id', 'name', 'address')->where('id', $id)->get();
            return response()->json($venue);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Venue not found'], 404);
        }
    }

    /**
     * Store and validate a newly created venue
     *
     * @param StoreVenueRequest $request
     * @return JsonResponse
     */
    public function store(StoreVenueRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $venue = new Venue([
            'name' => $validatedData['name'],
            'address' => $validatedData['address']
        ]);

        $venue->save();

        return response()->json($venue, 201);
    }

    /**
     * Update the specified venue
     *
     * @param UpdateVenueRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateVenueRequest $request, $id): JsonResponse
    {
        try {
            $venue = Venue::findorFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Venue not found'], 404);
        }

        $validatedData = $request->validated();

        $venue->update([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
        ]);

        return response()->json($venue);
    }

    /**
     * Delete the specified venue
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $venue = Venue::findorFail($id);
            
            $venue->delete();
            return response()->json(['message' => 'Venue deleted successfully']);     
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Venue not found'], 404);
        }
    }
}
