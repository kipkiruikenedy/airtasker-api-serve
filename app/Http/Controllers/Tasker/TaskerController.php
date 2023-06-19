<?php

namespace App\Http\Controllers\Tasker;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;

class TaskerController extends Controller
{

    public function updateTaskStatus(Request $request, $taskId)
    {
        try {
            // find the task by id
            $task = Task::findOrFail($taskId);
    
            // update the task status
            $task->status = $request->status;
            $task->save();
    
            // return success response
            return response()->json([
                'task' => $task,
                'message' => 'Task status updated successfully'
            ], 200);
    
        } catch (\Throwable $th) {
            // return error response
            return response()->json(['message' => "Sorry!!,Something went wrong during task status update, please try again later"], 500);
        }
    }
    

    public function pendingTasks(Request $request)
    {
        $user_id =$request->user_id;

        $tasks = Task::where('tasker_id',$user_id)
        ->where('status', 'assigned')
        ->latest()
        ->get();
        return response()->json($tasks, 200);
      
    }


    public function InProgressTasks(Request $request)
    {
        $user_id =$request->user_id;

        $tasks = Task::where('tasker_id',$user_id)
        ->where('status', 'assigned')
        ->latest()
        ->get();
        return response()->json($tasks, 200);
      
    }
    public function RequestedPayTasks(Request $request)
    {
        $user_id =$request->user_id;

        $tasks = Task::where('tasker_id',$user_id)
        ->where('status', 'requestedPayment')
        ->latest()
        ->get();
        return response()->json($tasks, 200);
      
    }


    public function paidTasks(Request $request)
    {
        $user_id =$request->user_id;

        $tasks = Task::where('tasker_id',$user_id)
        ->where('status', 'paid')
        ->latest()
        ->get();
        return response()->json($tasks, 200);
      
    }

  public function activeTasks(Request $request)
{
    $user_id = $request->user_id;

    $tasks = Task::where('tasker_id', $user_id)
        ->whereIn('status', ['assigned', 'requestedPayment','paid'])
        ->latest()
        ->get();

    return response()->json($tasks, 200);
}



    public function activeTasksById($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function completedTasks(Request $request)
    {
        $user_id =$request->user_id;

        $tasks = Task::where('tasker_id',$user_id)
        ->where('status', 'completed')
        ->latest()
        ->get();
        return response()->json($tasks, 200);
      
    }

    public function getActiveTask($id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }







    public function register(Request $request)
    {
        // Define validation rules
        $rules = [
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email|string',
            'phone_number' => 'required|numeric|min:10|unique:users,phone_number',
            'country' => 'required|string',
            'gender' => 'required|string',
            'password'=> [
                'required',
                'string',
                'min:6',
                'confirmed',
               
            ],
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
    


        // 44: This is the country code for the United Kingdom (including England, Scotland, Wales, and Northern Ireland).
        // 61: This is the country code for Australia, including external territories like Christmas Island and the Cocos (Keeling) Islands.
        // 64: This is the country code for New Zealand, including the Cook Islands, Niue, and Tokelau.
        // 65: This is the country code for Singapore.


        // Check if the phone number belongs to a supported country
        $number = $request->phone_number;
        $country_code = substr($number, 0, 2);
        $supported_country_codes = ['44', '61', '64', '65','04',];
    
        if (!in_array($country_code, $supported_country_codes)) {
            return response()->json([
                'message' => "Sorry, we currently don't accept applicants from your country, try again later"
            ], 400);
        }
    
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'country' => $request->country,
                'gender' => $request->gender,
                // Assign the role of "tasker" to the user
                'role_id' => 'tasker',
                'password' => Hash::make($request->password),
              
       
            ]);
    
            // Store the profile photo if it was provided
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/profile_photos', $filename);
                $user->profile_photo = $filename;
                $user->save();
            }

             // Disable account until email is verified
        $user->is_disabled = true;
        if ($user->save()) {
            // Send email to admin
            Try{
                $userData = [
                    'role' => 'Tasker',
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'phone' => $user->phone_number,
                    'country' => $user->country,
                    'email' => $user->email
                ];
    
                Mail::send('emails.new_user_registered', $userData, function ($message) {
                    $message->from("support@airtaska.com")
                        ->to("airtaska@gmail.com") // Replace with the admin's email address
                        ->subject('New User Registration - Airtaska');
                });
                return response()->json([
                    'user' => $user,
                    'message' => 'Congratulations! Your account has been created successfully.Email have been send to you.'
                ], 200);
            }catch (\Exception $e){
                return response()->json([
                    'user' => $user,
                    'message' => 'Congratulations! Your account has been created successfully.'
                ], 200);
            }
          

          
        }
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong during registration, please try again later'
        ], 500);
    }
    }
    


   
    


}
