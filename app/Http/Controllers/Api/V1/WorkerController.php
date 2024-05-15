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


class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new WorkersFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $includeTickets = $request->query('includeTickets');
        $includeFiles = $request->query('includeFiles');
        $workers = Worker::where($filterItems);

        if($includeTickets){
            $workers = $workers->with('tickets');
        }
        if($includeFiles){
            $workers = $workers->with('tickets.files');
        }
        return new WorkerCollection($workers->paginate()->appends($request->query()));


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
        }    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
        $includeTickets = request()->query('includeTickets');

        if($includeTickets){
            return new WorkerResource($worker->loadMissing('tickets'));
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
        //
    }
}
