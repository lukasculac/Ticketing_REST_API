<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\AgentsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreAgentRequest;
use App\Http\Requests\V1\StoreAgentRequest;
use App\Http\Requests\V1\UpdateAgentRequest;
use App\Http\Resources\V1\AgentCollection;
use App\Http\Resources\V1\AgentResource;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\WorkerCollection;
use App\Models\Agent;
use App\Models\Ticket;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $agent = auth('sanctum')->user();
        if (!$agent) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $ticketController = new TicketController;
        $ticketController->updateTicketPriorities();

        $tickets = Ticket::with('files')->get();

        return response()->json([
            'agent' => new AgentResource($agent),
            'tickets' => TicketResource::collection($tickets)
        ]);
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
    public function store(StoreAgentRequest $request)
    {
        return new AgentResource(Agent::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        return new AgentResource($agent);
    }


    public function bulkStore(BulkStoreAgentRequest $request)
    {
        $bulk = collect($request->all());

        Agent::create($bulk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        {
            $agent->update($request->all());
            return new AgentResource($agent);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return response()->json(null, 204);
    }
}
