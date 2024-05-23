<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\AgentsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreAgentRequest;
use App\Http\Requests\V1\StoreAgentRequest;
use App\Http\Requests\V1\UpdateAgentRequest;
use App\Http\Resources\V1\AgentCollection;
use App\Http\Resources\V1\AgentResource;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new AgentsFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $agents = Agent::where($filterItems);


        return new AgentCollection($agents->paginate()->appends($request->query()));
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
