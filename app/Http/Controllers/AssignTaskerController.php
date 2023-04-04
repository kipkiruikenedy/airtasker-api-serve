<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssignTaskerController extends Controller
{
    public function assignTask(Request $request)
{
    $validatedData = $request->validate([
        'task_id' => 'required|exists:tasks,id',
        'tasker_id' => 'required|exists:users,id',
    ]);

    $task = Task::find($validatedData['task_id']);

    if ($task->tasker_id) {
        return response()->json(['message' => 'Task has already been assigned to a tasker'], 400);
    }

    $task->tasker_id = $validatedData['tasker_id'];
    $task->status = 'assigned';
    $task->save();

    return response()->json(['message' => 'Task assigned to tasker'], 200);
}

}
