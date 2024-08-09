<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\WorkersFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreWorkerRequest;
use App\Http\Requests\V1\UpdateWorkerRequest;
use App\Http\Resources\V1\WorkerCollection;
use App\Http\Resources\V1\WorkerResource;
use App\Models\Ticket;
use App\Models\Worker;
use App\Http\Requests\V1\BulkStoreWorkerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $worker = auth('sanctum')->user();
        if (!$worker) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $worker->load('tickets.files');
        return new WorkerResource($worker);
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
    public function store(StoreWorkerRequest $request)
    {
        return new WorkerResource(Worker::create($request->all()));
    }

    public function bulkStore(BulkStoreWorkerRequest $request)
    {
        $bulk = collect($request->all())->map(function($arr, $key){
            return Arr::except($arr, ['workerId', 'openedId', 'closedId']);
        });

        foreach ($bulk as $ticketData) {
            Ticket::create($ticketData);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
        $authenticatedWorker = auth()->guard('worker')->user();

        // Check if there is an authenticated worker
        if (!$authenticatedWorker) {
            // If not, return a 401 Unauthorized response
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($authenticatedWorker->id !== $worker->id) {
            // If not, return a 403 Forbidden response
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $includeTickets = request()->query('includeTickets');
        $includeFiles = request()->query('includeFiles');

        if($includeTickets){
            $worker->loadMissing('tickets');
        }
        if($includeFiles){
            $worker->loadMissing('tickets.files');
        }

        return new WorkerResource($worker);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkerRequest $request, Worker $worker)
    {
        $worker->update($request->all());
        return new WorkerResource($worker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        $worker->delete();
        return response()->json(null, 204);
    }
}
