<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
 
    public function login(Request $request)
    {
        $credentials =$request->validate([  'email' => ['required', 'email'],
        'password' => ['required', 'min:6']]);
            
          
       try {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = 123456;
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'error' => 'Invalid credentials, please try again'
            ], 401);
        }
       } catch (\Throwable $th) {
        return response()->json([
            'error' => 'server error'
        ], 401);
       }
    
       
    }
    
        
        
    }
