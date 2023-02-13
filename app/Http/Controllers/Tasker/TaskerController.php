<?php

namespace App\Http\Controllers\Tasker;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TaskerController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'min:3'],
                'last_name' => ['required', 'string', 'min:3'],
                'email' => ['required', 'email'],
                'phone_number' => ['required'],
                'country' => ['required', 'string'],
                'gender' => ['required', 'string'],
                'password' => ['required', 'min:6','confirmed']
            ]);

            if ($validator->fails()) {
                // Return validation errors
                return response(['errors' => $validator->errors()->all()], 422);
            }
//  Check if the phone number 
// The code numbers for Australia, UK, Singapore, and New Zealand are:
//     Australia: +61
//     UK: +44
//     Singapore: +65
//     New Zealand: +64
 $number =$request->phone_number;
 $country_code =substr($number, 0, 2);


 if (!in_array($country_code, ['44', '61','64','65'])) {
    return response()->json(['error' => "Sorry, we currently don't accept applicants from your country, try again later"], 400);
 } else {
    $user=User::create([
        'first_name'=>$request->first_name,
        'last_name'=>$request->last_name,
        'email'=>$request->email,
        'phone_number'=>$request->phone_number,
        'country'=>$request->country,
        'gender'=>$request->gender,
          // Assign the role of "tasker" to the user
        'role_id'=>'tasker',
        'password'=>Hash::make($request->password)
    ]);

    return response()->json([
        'user'=>$user,
        'msg'=>'register successfully'
    ],201);
 }
 


        } catch (\Throwable $th) {
            // Return error response
            return response()->json(['error' => "Sorry!!,Something went wrong during registration, please try again later"], 500);
        }
    }
}