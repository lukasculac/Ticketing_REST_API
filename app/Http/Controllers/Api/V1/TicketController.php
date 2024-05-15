<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TicketsFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTicketRequest;
use App\Http\Requests\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketCollection;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new TicketsFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $includeFiles = $request->query('includeFiles');
        $tickets = Ticket::where($filterItems);

        if($includeFiles){
            $tickets = $tickets->with('files');
        }
        return new TicketCollection($tickets->paginate()->appends($request->query()));
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
        $includeFiles = request()->query('includeFiles');
        if($includeFiles){
            return new TicketResource($ticket->loadMissing('files'));
        }
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
