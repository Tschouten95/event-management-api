<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TicketResource;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Exception;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Exceptions\HttpResponseException;
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


    /**
     * A function that returns a specific ticket
     * 
     * @param Int $id
     * @return JsonResponse
     */
    public function show(Int $id): JsonResponse
    {
        $ticket = $this->findTicketOrFail($id);

        return response()->json(new TicketResource($ticket));
    }


    /**
     * Store and validate a newly created ticket
     * 
     * @param StoreTicketRequest $request
     * @return JsonResponse
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $ticket = new Ticket($validatedData);

        $ticket->save();

        return response()->json(new TicketResource($ticket), 201);
    }

    /**
     * Update the specified ticket
     * 
     * @param UpdateTicketRequest $request
     * @param Int $id
     * @return JsonResponse
     */
    public function update(UpdateTicketRequest $request, $id): JsonResponse
    {
            $ticket = $this->findTicketOrFail($id);

            $validatedData = $request->validated();

            $ticket->update($validatedData);

            return response()->json(new TicketResource($ticket), 200);
    }


    /**
     * Delete the specified ticket
     * 
     * @param Int $id
     * @return JsonResponse
     */
    public function destroy(Int $id): JsonResponse
    {
            $ticket = $this->findTicketOrFail($id);
            $ticket->delete();
            
            return response()->json(["Message" => "Ticket deleted succesfully"], 200);
    }

    /**
     * A function that determines wether the ticket exists in the database and throws a error if it doesnt
     * 
     * @param int $id
     * @return Ticket
     */
    private function findTicketOrFail(Int $id): Ticket
    {
        return Ticket::with('event')->findOrFail($id);
    }
}
