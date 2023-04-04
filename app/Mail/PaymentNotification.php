<?php

namespace App\Mail;

use App\Models\ClientPayTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $task;

    public function __construct(ClientPayTask $payment)
    {
        $this->payment = $payment;
        $this->task = $payment->task;
    }

    public function build()
    {
        $subject = "Payment for task " . $this->payment->task->title . " Received";
        $email = $this->payment->client->email ?? ''; // use default empty string if email is null
        $name = $this->payment->client->first_name ?? '';
        
        if (!empty($email)) {
            $to[] = [
                'email' => $email,
                'name' => $name,
            ];
        }
        
        $to[] = [
            'email' => env('ADMIN_EMAIL'),
            'name' => 'Admin',
        ];
        
        
        return $this->to($to)
                    ->subject($subject)
                    ->view('emails.payment-notification', ['task' => $this->task]); // pass the variable $task to the view
    }
}
