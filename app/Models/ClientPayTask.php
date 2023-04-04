<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;



class ClientPayTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'tasker_id',
        'client_id',
        'amount',
        'status',
        'stripe_token',

     
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class);
    }

    public function charge()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $token = Token::retrieve($this->stripe_token);
        $charge = Charge::create([
            'amount' => $this->amount,
            'currency' => 'usd',
            'description' => 'Charge for name' ,
            // 'description' => 'Charge for ' . $this->user->name,
            'source' => $token,
        ]);

        return $charge;
    }


  
}
