<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\Offer;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class ClientController extends Controller
{
    public function clientOwnRequestedPaymentTasks(Request $request)
    {
        $user_id = $request->user_id;

        $tasks = Task::where('client_id', $user_id)
            ->where('status', 'requestedPayment')
            ->latest()
            ->get();

        $total_tasks = $tasks->count();

        return response()->json([
            'tasks' => $tasks,
            'total_tasks' => $total_tasks
        ], 200);
    }



    public function clientOwnCompletedTasks(Request $request)
    {
        $user_id = $request->user_id;

        $tasks = Task::where('client_id', $user_id)
            ->where('status', 'completed')
            ->latest()
            ->get();
        return response()->json($tasks, 200);
    }



    public function clientOwnRejectedTasks(Request $request)
    {
        $user_id = $request->user_id;

        $tasks = Task::where('client_id', $user_id)
            ->where('status', 'rejected')
            ->latest()
            ->get();
        return response()->json($tasks, 200);
    }

    public function clientOwnTasks(Request $request)
    {
        $user_id = $request->user_id;

        $tasks = Task::where('client_id', $user_id)
            ->where('status', 'OPEN')
            ->latest()
            ->get();
        return response()->json($tasks, 200);
    }



    public function clientOwnActiveTasks(Request $request)
    {
        $user_id = $request->user_id;

        $tasks = Task::where('client_id', $user_id)
            ->whereIn('status', ['assigned', 'in-progress'])
            ->latest()
            ->get();
        return response()->json($tasks, 200);
    }



    public function clientOwnTaskOffers(Request $request)
    {
        $task_id = $request->task_id;

        $offer = Offer::where('task_id', $task_id)

            ->get();

        return response()->json($offer, 200);
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
            'password' => [
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
    
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'country' => $request->country,
                'gender' => $request->gender,
                // Assign the role of "" to the user
                'role_id' => 'client',
                'password' => Hash::make($request->password)
            ]);
    
            // Store the profile photo if it was provided
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/profile_photos', $filename);
                $user->profile_photo = $filename;
            }
    
            if ($user->save()) {
                // Send email to admin
                Try{
                    $userData = [
                        'role' => 'Client',
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
                    $user_id = $user->id;

                    $notification = Notification::create([
                        'user_id' => $user_id,
                        'title' => 'Account Activation',
                        'message' => 'Please activate your account.',
                        'status' => 0
                    ]);
                    
                    return response()->json([
                        'user' => $user,
                        'notification'=>$notification,
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
    public function update(Request $request)
    {
        // Define validation rules
        $rules = [
            'id' => 'required',
            'first_name' => 'required|string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email|string',
            'phone_number' => 'required|numeric|min:10',
            'country' => 'required|string',
            'gender' => 'required|string',
            'card_number' => 'required',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
    
        try {
            $user = User::findOrFail($request->id); 
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->country = $request->country;
            $user->gender = $request->gender;
            $user->card_number = $request->card_number;
    
            // Update the password if it was provided
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
    
            // Store the profile photo if it was provided
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/profile_photos', $filename);
                $user->profile_photo = $filename;
            }
    
            if ($user->save()) {
                return response()->json([
                    'user' => $user,
                    'message' => 'Your Details have been updated successfully.',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during update, please try again later',
            ], 500);
        }
    }
    


  

    public function userDetails($id)
    {
        $user = User::find($id);
        return response()->json([
            "user" => $user
        ]);
    }

 
}
