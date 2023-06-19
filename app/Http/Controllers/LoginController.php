<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  


    public function login(Request $request)
    {
        // Check the request for a valid user email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'No user found with this email'], 404);
        }

        // Check the password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $accessToken = $user->createToken('authToken')->accessToken;
            $refreshToken = $user->createToken('refreshToken', ['refresh-token'])->accessToken;

            $data = [
                'user'          => $user,
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type'    => 'Bearer',
                'expires_at'    => Carbon::parse($accessToken->token->expires_at)->toDateTimeString()
            ];

            return response()->json([ 'message' => 'Logged in successfully.'], 200);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }
}
