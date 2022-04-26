<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',
//            'phone' => 'required|unique:users',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'msg' => 'User register successfully',
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($data)) {
            return response()->json([
               'error' => "Credentials not match",
            ]);
        }

        return response()->json([
           'msg' => "User Login Successfully",
           'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successfully'
        ], 200);
    }

}
