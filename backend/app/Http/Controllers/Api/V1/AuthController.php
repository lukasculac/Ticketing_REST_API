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
            $worker = new Worker;
            $worker->name = $request->name;
            $worker->email = $request->email;
            $worker->password = Hash::make($request->password);
            $worker->position = $request->position;
            $worker->phone = $request->phone;
            $worker->save();

            return new WorkerResource($worker);
        } elseif ($request->user_type == 'agent') {
            $agent = new Agent;
            $agent->name = $request->name;
            $agent->email = $request->email;
            $agent->password = Hash::make($request->password);
            $agent->position = $request->position;
            $agent->phone = $request->phone;
            $agent->save();

            return new AgentResource($agent);
        }
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $guard = $request->user_type; // 'worker' or 'agent'

        // Retrieve the user from the database
        $userModel = $guard == 'worker' ? Worker::class : Agent::class;
        $user = $userModel::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Log::info('Password is correct');

            // Manually generate a token
            $token = $user->createToken($guard . '_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'message' => 'Logged in successfully'
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);


        /*
        if($request->user_type == 'worker'){
            if(!Auth::guard('worker')->attempt($request->only('email', 'password'))){
                return response([
                    'message' => 'Invalid credentials!'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = Auth::guard('worker')->user();

            $token = $user->createToken('worker_token')->plainTextToken;
            Log::info('Token: ' . $token);

            $cookie = cookie('jwt', $token, 60*24, null, null, false, false); // 1 day // 1 day

            return response([
                'message' => 'success',
                'token' => $token
            ])->withCookie($cookie);

        }
        elseif($request->user_type == 'agent'){
            if(!Auth::guard('agent')->attempt($request->only('email', 'password'))){
                return response([
                    'message' => 'Invalid credentials!'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = Auth::guard('agent')->user();

            $token = $user->createToken('agent_token')->plainTextToken;
            return $token;
        }
        */
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
        $user = null;

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
