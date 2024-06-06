<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AgentResource;
use App\Http\Resources\V1\WorkerResource;
use App\Models\Agent;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->user_type == 'worker') {
            //check if user exists
            $existingWorker = Worker::where('email', $request->email)->first();
            if ($existingWorker) {
                return response()->json(['message' => 'A worker with this email already exists.'], 409);
            }

            $worker = new Worker;
            $worker->name = $request->name;
            $worker->email = $request->email;
            $worker->password = Hash::make($request->password);
            $worker->position = $request->position;
            $worker->phone = $request->phone;
            $worker->save();

            return new WorkerResource($worker);
        }
        return null;
    }


    public function login(Request $request)
    {
        $guard = $request->user_type; // 'worker' or 'agent'
        // Retrieve the user from the database
        $userModel = $guard == 'worker' ? Worker::class : Agent::class;
        $user = $userModel::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['message' => 'No user found. Please register!'], 401); //user doesnt exist
        }

        if ($user && Hash::check($request->password, $user->password)) {
            //generate a token
            $token = $user->createToken($guard . '_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'message' => 'Logged in successfully'
            ]);
        }
        return response()->json(['message' => 'Invalid credentials'], 401); //wrong password
    }

    public function worker()
    {
        return Auth::guard('worker')->user();
    }

    public function agent()
    {
        return Auth::guard('agent')->user();
    }

    public function logout(Request $request)
    {
        $guard = $request->user_type;

        if ($guard == 'worker') {
            $user = Auth::guard('sanctum')->user();
        } elseif ($guard == 'agent') {
            $user = Auth::guard('sanctum')->user();
        } else {
            return response()->json(['message' => 'Invalid user type'], 400);
        }

        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}
