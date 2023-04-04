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
        $task = Task::find($validatedData['task_id']);

        $payment = ClientPayTask::create([
            'task_id' => $validatedData['task_id'],
            'tasker_id' => $validatedData['tasker_id'],
            'client_id' => $validatedData['client_id'],
            'amount' => $validatedData['amount'],
            'status' => 'pending',
            'stripe_token' => $validatedData['stripe_token'],
        
        ]);

        try {
            $payment->charge();
            $payment->update(['status' => 'paid']);
          

 //Update task status and tasker ID
            $task->tasker_id = $validatedData['tasker_id'];
            $task->status = 'assigned';
            $task->save();
    
            // $client_email = $payment->client->email;
            // $admin_email = config('mail.admin_email');

            // Mail::to($client_email)->cc($admin_email)->send(new PaymentNotification($payment));
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            // $client_email = $payment->client->email;
            // $admin_email = config('mail.admin_email');
            // Mail::to($client_email)->cc($admin_email)->send(new PaymentNotification($payment));
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['message' => 'Payment successful'], 200);
    }
    public function update(Request $request, $id)
    {
        $payment = ClientPayTask::findOrFail($id);
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['paid', 'failed'])],
        ]);
    
        $payment->status = $validatedData['status'];
        $payment->save();
    
        return response()->json(['message' => 'Payment status updated'], 200);
    }
    
  
}
