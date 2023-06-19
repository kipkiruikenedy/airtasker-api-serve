<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Config\Email;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
   public function sendForgotPasswordEmail(Request $request)
   {
      $data = [];
      if ($request->isMethod('post')) {
         $rules = [
            'email' => 'required|email',
         ];

         $email = $request->input('email');
         $userData = User::where('email', $email)->first();
         if (!$userData) {
            return response()->json([
               'message' => 'User with' . $email . 'not found in the system'
            ]);
         }

         if ($userData != null && $userData->email == $email) {
            $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            $userData->otp = $otp; // Update the user's record

            if ($userData->save()) {
               $emailData = [
                  'firstName' => $userData->first_name,
                  'otp' => $otp,
               ];
               Mail::send('emails.forgot_password', $emailData, function ($message) use ($email) {
                  $message->from("support@airtaska.com")
                     ->to($email)
                     ->subject('Password Reset - Airtaska');
               });
               return response()->json(['message' => 'A link has been sent to your email: ' . $email]);
            } else {
               return response()->json(['message' => 'Email not found']);
            }
         } else {
            $data['validation'] = $request->validate([
               'email' => 'exists:users,email',
            ]);
         }
      }
   }




   public function otp(Request $request)
   {
      $data = [];
      if ($request->isMethod('post')) {
         $rules = [
            'otp' => 'required',
         ];

         $otp = $request->input('otp');
         $userData = User::where('otp', $otp)->first();
      }
   }

   public function changePassword(Request $request)
   {
      $token = $request->input('token');

      if (!empty($token)) {
         $client = User::where('token', 'LIKE', $token)->first();

         if ($client != null) {
            $decodedToken = json_decode($client->token);

            if ($decodedToken) {
               $userData = [
                  'clientID' => $client->clientID,
                  'password' => $request->input('password'),
                  'token' => null
               ];

               $validationRules = [
                  'password' => 'required|min:8'
               ];

               $validator = Validator::make($userData, $validationRules);

               if ($validator->fails()) {
                  return response()->json([
                     'success' => false,
                     'message' => 'Password must be at least 8 characters long'
                  ]);
               }

               if ($client->update($userData)) {
                  return response()->json([
                     'success' => true,
                     'message' => 'Password reset successfully'
                  ]);
               } else {
                  return response()->json([
                     'success' => false,
                     'message' => 'Password reset failed'
                  ]);
               }
            } else {
               return response()->json([
                  'success' => false,
                  'message' => 'Reset link has expired'
               ]);
            }
         } else {
            return response()->json([
               'success' => false,
               'message' => 'User not found'
            ]);
         }
      } else {
         return response()->json([
            'success' => false,
            'message' => 'Unauthorized access'
         ]);
      }
   }
}
