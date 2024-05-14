<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TicketsFIlter;
use App\Filters\V1\WorkersFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\V1\TicketCollection;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\WorkerCollection;
use App\Models\Ticket;
use App\Models\Worker;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new TicketsFilter();
        $queryItems = $filter->transform($request); //[['column', 'operator', 'value']]
        if (count($queryItems) == 0) {
            return new TicketCollection (Ticket::paginate());
        } else {
            $workers = Ticket::where($queryItems)->paginate();
            return new TicketCollection ($workers->appends($request->query()));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
