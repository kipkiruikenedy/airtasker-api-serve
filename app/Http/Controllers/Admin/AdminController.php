<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{

    public function clients()
    {
        $clients = User::where('role_id','client')->get();
        return response()->json($clients, 200);
    }
    public function taskers()
    {
        $taskers = User::where('role_id','tasker')->get();
        return response()->json($taskers, 200);
    }
    public function tasks()
    {
        //
    }
    public function client_tasks()
    {
        //
    }
    public function tasker_tasks()
    {
        //
    }
}
