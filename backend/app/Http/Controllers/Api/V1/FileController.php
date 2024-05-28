<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\FilesFIlter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFileRequest;
use App\Http\Requests\V1\UpdateFileRequest;
use App\Http\Resources\V1\FileCollection;
use App\Http\Resources\V1\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new FilesFilter();
        $queryItems = $filter->transform($request); //[['column', 'operator', 'value']]
        if (count($queryItems) == 0) {
            return new FileCollection (File::paginate());
        } else {
            $files = File::where($queryItems)->paginate();
            return new FileCollection ($files->appends($request->query()));        }
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
    public function store(StoreFileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        Storage::delete($file->path);
        $file->delete();
        return response()->json(null, 204);
    }
}
