<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    public function setup()
    {
        $credentials = [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ];
        if (!Auth::attempt($credentials)) {
            $user = new \App\Models\User();
            $user->name = 'Admin';
            $user->email = $credentials['email'];
            $user->password = Hash::make($credentials['password']);
            $user->save();

            if(Auth::attempt($credentials)) {
                $user = Auth::user();

                $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
                $updateToken = $user->createToken('update-token', ['create', 'update']);
                $basicToken = $user->createToken('basic-token', ['create']);

                return [
                    'admin' => $adminToken->plainTextToken,
                    'update' => $updateToken->plainTextToken,
                    'basic' => $basicToken->plainTextToken,
                ];
            };
        }
    }
    */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:workers',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $worker = new Worker([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'phone' => $request->phone,
            // Add other fields as needed
        ]);

        $worker->save();

    }
}
