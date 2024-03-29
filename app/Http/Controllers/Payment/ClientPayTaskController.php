<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientPayTask;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use App\Mail\PaymentNotification;





class ClientPayTaskController extends Controller
{

    public function ClientPay(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required',
            'client_id' => 'required',
            'tasker_id' => 'required',
            'amount' => 'required',
            'stripe_token' => 'required',
        ]);

        // Round off the amount to 4 decimal places
        $roundedAmount = round($validatedData['amount'], 4);

        $task = Task::find($validatedData['task_id']);

        // Check if payment already exists for task and client with "paid" status
        $existingPayment = ClientPayTask::where('task_id', $validatedData['task_id'])
            ->where('client_id', $validatedData['client_id'])
            ->where('status', 'paid')
            ->first();
        if ($existingPayment) {
            return response()->json(['message' => 'Payment already made'], 200);
        }

        $payment = ClientPayTask::create([
            'task_id' => $validatedData['task_id'],
            'tasker_id' => $validatedData['tasker_id'],
            'client_id' => $validatedData['client_id'],
            'amount' => $roundedAmount, // Use the rounded amount
            'status' => 'pending',
            'stripe_token' => $validatedData['stripe_token'],

        ]);

        try {
            $payment->charge();
            $payment->update(['status' => 'paid']);


            //Update task status and tasker ID
            $task->tasker_id = $validatedData['tasker_id'];
            $task->status = 'assigned';


          
            
            if ($task->save()) {
                $client_email = $payment->client->email;
                $admin_email = 'airtaska@gmail.com';
            
                Mail::to($client_email)->cc($admin_email)->send(new PaymentNotification($payment));
            
                Mail::send([], [], function ($message) use ($client_email, $admin_email) {
                    $message->from("airtaska@gmail.com", "Airtaska Support")
                        ->to($admin_email)
                        ->subject('Payment Notification - Airtaska')
                        ->setBody("A payment notification has been sent to the client at $client_email.");
                });
            }
            

            return response()->json(['message' => 'Congratulations!, Payment successful'], 200);
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            $client_email = $payment->client->email;
            $admin_email = config('mail.admin_email');
            Mail::to($client_email)->cc($admin_email)->send(new PaymentNotification($payment));
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['message' => 'Payment successful'], 200);
    }
}
