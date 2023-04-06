<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Offer;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    




  
    public function allPendingTasks()
    {
        $tasks = Task::where('status','pending') ->latest()->get();
        return response()->json($tasks, 200);
    }

    public function allTasks()
    {
        
        $tasks = Task::latest()->get();
        return response()->json($tasks, 200);
        
    }


    public function OpenTasks()
    {
        $tasks = Task::where('status','OPEN')->latest()->get();
        return response()->json($tasks, 200);
    }

    
    public function allActiveTasks()
    {
        $tasks = Task::where('status','active')->latest()->get();
        return response()->json($tasks, 200);
    }
    public function allCompletedTasks()
    {
        $tasks = Task::where('status','completed')->latest()->get();
        return response()->json($tasks, 200);
    }
    public function allRejectedTasks()
    {
        $tasks = Task::where('status','rejected')->latest()->get();
        return response()->json($tasks, 200);
    }
    public function allAsignedTasks()
    {
        $tasks = Task::where('status','asigned')->latest()->get();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'string',
            'time' => 'string',
            'client_id'=>'numeric',
            
        ]);
    
    
        $task=Task::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'amount'=>$request->amount,
            'category_id'=>$request->category_id,
            'deadline'=>$request->deadline,
            'time'=>$request->time,
            'client_id'=>$request->client_id,
        ]);
    
        return response()->json([
            'task'=>$task,
            'message'=>'task added successfully'
        ],201);   
    }







    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findTaskByID($id)
    {
        $task = Task::with('client','tasker')->find($id);
    
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        $amount = $task->amount;
        $fees = $amount * 0.16;
        $receivable = $amount - $fees;
        $amountPayable = $amount + $fees;

        $task->amount = $amount;
        $task->fees = $fees;
        $task->receivable = $receivable;
        $task->amountPayable = $amountPayable;
    
        return response()->json($task);
    }
    

    


    public function deleteById($id)
    {
        $task = Task::findOrFail($id);
    
        // Check if the authenticated user is the owner of the task
        // if ($task->user_id !== auth()->id()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
    
        // Cancel the task
        $task->status = 'cancelled';
        $task->save();
    
        return response()->json(['message' => 'Task cancelled successfully']);
    }
    



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
