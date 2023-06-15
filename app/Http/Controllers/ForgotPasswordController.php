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

         $validatedData = $request->validate($rules);
         if ($validatedData) {
            $email = $request->input('email');
            $userData = User::where('email', $email)->first();

            if ($userData != null && $userData->email == $email) {
               // Generate a reset token and update the user's record
               $token = Str::random(32);

               $userData->token = $token; // Update the user's record

               if ($userData->save()) {
                  $token = urlencode($token); // Encode the token for URL safety
                  $url = url('/account/login/reset_password?token=' . $token);

                  $emailMessage = "<b>Hello " . $userData->firstName . "</b>\n\n" .
                     "You requested to reset your password. If you did not authorize this, simply ignore this email.\n\n" .
                     "But if you did, click <b><a href='$url'>Here</a></b> to reset your password. If that doesn't work, copy-paste this link to your browser: " .
                     "<a href='$url'>$url</a>";
                  Mail::send([], [], function ($message) use ($emailMessage, $email) {
                     $message->from("support@airtaska.com")
                        ->to($email)
                        ->subject('Password Reset - Airtaska')
                        ->html($emailMessage);
                  });



                  return response()->json(['success' => true, 'message' => 'A link has been sent to your email: ' . $email]);
               } else {
                  return response()->json(['success' => false, 'message' => 'Email not found']);
               }
            } else {
               $data['validation'] = $request->validate([
                  'email' => 'exists:users,email',
               ]);
            }
         }
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
