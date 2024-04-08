<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TicketResource;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
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
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $event = Ticket::with('event')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json(new TicketResource($event));
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

        $ticket = new Ticket([
            'event_id' => $validatedData['event_id'],
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'quantity_available' => $validatedData['quantity_available']
        ]);

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
        try {
            $ticket = Ticket::findOrFail($id);

            $validatedData = $request->validated();

            $ticket->update([
                'event_id' => $validatedData['event_id'],
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'quantity_available' => $validatedData['quantity_available']
            ]);

            return response()->json(new TicketResource($ticket), 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }
    }


    /**
     * Delete the specified ticket
     * 
     * @param Int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {

            $ticket = $this->findTicketOrFail($id);
            $ticket->delete();
            
            return response()->json(["Message" => "Ticket deleted succesfully", 204]);
    }

    private function findTicketOrFail($id): Ticket
    {
        try {
            return Ticket::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new HttpResponseException(response()->json(['error' => 'Ticket not found'], 404));
        }
    }
}
