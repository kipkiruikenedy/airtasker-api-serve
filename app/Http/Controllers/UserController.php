<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function Logout(Request $request)
    {
        // Revoke the access token
        $request->user()->token()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getUser(Request $request)
    {
        // Retrieve the user ID from the authenticated token
        $userId = $request->user()->id;

        // Fetch the user data from the database
        $user = User::find($userId);

        // Return the user data
        return response()->json(['user'=>$user,'message' => 'Logged in successfully.']);
    }

    public function getUsers($id)
    {
        $users = User::all();
        return response()->json($users);
    }
    public function allUsers($id)
    {
        $users = User::all();
        return response()->json($users);
    }

    public function UserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        return response()->json($user);
    }
}
