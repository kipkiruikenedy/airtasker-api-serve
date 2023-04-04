<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function getUsers($id)
    {
        $users = User::where('id', '!=', $id)->get();
        return response()->json($users);
    }
}
