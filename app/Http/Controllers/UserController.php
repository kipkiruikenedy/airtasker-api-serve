<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
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
