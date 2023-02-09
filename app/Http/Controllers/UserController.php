<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request){

        try {
            $validator=Validator::make($request->all(),[
                'first_name'=>'required|string|min:3',
                'last_name'=>'required|string|min:3',
                'email'=>'required|email|unique:users,email|string',
                'phone_number'=>'required|string|min:10',
                'country'=>'required|string',
                'gender'=>'required|string',
                'role_id'=>'required|string',
                'password'=>'required|min:6|confirmed'
            ]);
    
            if($validator->fails()){
                //throw back any errors of validation if they arise
                return response(['errors'=>$validator->errors()->all()], 422);    
            }
    
                   
        
                $user=User::create([
                    'role_id'=>$request->role_id,
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'email'=>$request->email,
                    'phone_number'=>$request->phone_number,
                    'country'=>$request->country,
                    'gender'=>$request->gender,
                    'role_id'=>$request->role_id,
                    'password'=>Hash::make($request->password)
                ]);
        
                return response()->json([
                    'user'=>$user,
                    'msg'=>'register successfully'
                ],201);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message'=>$th
            ]);
        }
    }

    public function login(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|string',
                'password' => 'required|min:6',
            ]);
    
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $user = User::where('email',$request->email)->first(); 
                $token=$user->createToken(time())->plainTextToken;

                $role=$user->role_id;

                if ($role==1) {
                    return response()->json([
                        'role'=>'admin',
                        'token'=>$token,
                        'user'=>$user
                    ],200); 
                } elseif ($role==2) {
                    return response()->json([
                        'role'=>'client',
                        'token'=>$token,
                        'user'=>$user
                    ],201); 
                } 
                elseif ($role==3) {
                    return response()->json([
                        'role'=>'writer',
                        'token'=>$token,
                        'user'=>$user
                    ],202); 
                } 
                
               
            } 
            else{ 
                return response()->json(['Unauthorized'=>'Email or/and password do not match our records'], 401);
            } 
    
    
            if($validator->fails()){
                return response()->json(['error' => $validator->errors()->all()]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message'=>$th
            ]);
        }
        
    }

    public function userDetails($id){
        $user=User::find($id);
        return response()->json([
            "user"=>$user
        ]);
    }

    public function destroy(Request $request)
    {
      // Get user who requested the logout.  Alternative a: Auth::user()->tokens()->where('id', $id)->delete();
    $user = request()->user(); //or Auth::user()

    // Revoke current user token
     $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

       if ($user ) {
        return response()->json([
            "message"=>"logged out "
        ],200);
       }



    }
    
}
