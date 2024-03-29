<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'content',
        'tasker_id' ,
        'task_id',
        'price',
    ];

    /**
     * Get the task that owns the Offer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
   

  

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function tasker()
    {
        return $this->belongsTo(User::class, 'tasker_id');
    }
}
