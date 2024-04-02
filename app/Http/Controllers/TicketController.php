<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TicketResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends Controller
{
    /**
     * A function that returns all tickets and their associated events
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tickets = Ticket::with('event')->get();

        return response()->json(TicketResource::collection($tickets));
    }

    public function show($id): JsonResponse
    {
        try {
            $event = Ticket::with('event')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Tickets not found'], 404);
        }

        return response()->json(new TicketResource($event));
    }
}
