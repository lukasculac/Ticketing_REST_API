<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\TicketsFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTicketRequest;
use App\Http\Requests\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketCollection;
use App\Http\Resources\V1\TicketResource;
use App\Models\File;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Get the currently authenticated worker
        $worker = auth('sanctum')->user();

        if (!$worker) {
            // If not, return a 401 Unauthorized response
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $filter = new TicketsFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $includeFiles = $request->query('includeFiles');

        // Filter the tickets based on the authenticated worker's ID
        $tickets = Ticket::where('worker_id', $worker->id)->where($filterItems);

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
    public function store(Request $request)
    {
        // Get the currently authenticated worker
        $worker = auth('sanctum')->user();
        //log::info($request);

        if (!$worker) {
            // If not, return a 401 Unauthorized response
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // Validate the request data
        $request->validate([
            'department' => 'required|string',
            'message' => 'required|string',
            'files.*' => 'file'
        ]);


        // Create a new ticket and associate it with the worker
        $ticket = new Ticket;
        $ticket->department = $request->department;
        $ticket->message = $request->message;
        $ticket->worker_id = $worker->id;
        $ticket->save();

        $ticket->refresh();

        // Check if there are any files in the request
        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $extension = $file->extension();
                $originalName = $file->getClientOriginalName() . '_' . $ticket->id . '.' . $extension;
                $path = $file->storeAs('files', $originalName);

                // Create a new file record and associate it with the ticket and the worker
                $fileRecord = new File;
                $fileRecord->file_name = $originalName;
                $fileRecord->path = $path;
                $fileRecord->ticket_id = $ticket->id;
                $fileRecord->worker_id = $worker->id;
                $fileRecord->save();
            }
        }
        return new TicketResource($ticket->load('files'));
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
    public function update(Request $request, Ticket $ticket)
    {
        Log::info($request);
        // Validate the request data
        $validatedData = $request->validate([
            'department' => 'required|string',
            'message' => 'required|string',
            'files.*' => 'file'
        ]);
        $ticket->update($validatedData);


        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                // Store the file and get its path
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $originalName = $filename . '_' . $ticket->id . '.' . $extension;
                $path = $file->storeAs('files', $originalName);

                // Check if file already exists in the database
                if (!File::where('file_name', $originalName)->exists()) {
                    // Create a new file record and associate it with the ticket
                    $fileRecord = new File;
                    $fileRecord->file_name = $originalName;
                    $fileRecord->path = $path;
                    $fileRecord->ticket_id = $ticket->id;
                    $fileRecord->worker_id = $ticket->worker_id;
                    $fileRecord->save();
                }
            }
        }

        return new TicketResource($ticket);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        foreach ($ticket->files as $file) {
            $file->delete();
            Storage::delete($file->path);
        }

        $ticket->delete();

        return response()->json(null, 204);
    }
}
