<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;

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
    public function taskerById($id)
    {
        $task = User::findOrFail($id);
        return response()->json(['task' => $task]);
    }
    public function clientById($id)
    {
        $task = User::findOrFail($id);
        return response()->json(['task' => $task]);
    }
   
    public function client_tasks()
    {
        //
    }
    public function tasker_tasks()
    {
        //
    }

    //DELETE USER
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    //UPDATE USER
    public function update(Request $request, $id)
{
    $tasker = User::find($id);
    if (!$tasker) {
        return response()->json(['message' => 'Tasker not found'], 404);
    }

    $tasker->fill($request->all());
    $tasker->save();

    return response()->json(['message' => 'Tasker updated successfully'], 200);
}

// CATEGORY
public function addCategory(Request $request)
{
   
    $validated = $request->validate([
        'job_category_name' => 'required|string',
    ]);


    $category=Category::create([
        'job_category_name'=>$request->job_category_name,
    ]);

    return response()->json([
        'category'=>$category,
        'message'=>'Category added successfully'
    ],201);
}


public function categories()
{
    $categories = Category::all();
    return response()->json($categories, 200);
}




public function allCompletedTask()
{

    $tasks = Task::where('status', 'completed')
    ->get();
    return response()->json($tasks, 200);
  
}
}
