<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\WorkersFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Http\Resources\V1\WorkerCollection;
use App\Http\Resources\V1\WorkerResource;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new WorkersFilter();
        $queryItems = $filter->transform($request); //[['column', 'operator', 'value']]
        if (count($queryItems) == 0) {
            return new WorkerCollection (Worker::paginate());
        } else {
            $workers = Worker::where($queryItems)->paginate();
            return new WorkerCollection ($workers->appends($request->query()));        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        //
    }
}
