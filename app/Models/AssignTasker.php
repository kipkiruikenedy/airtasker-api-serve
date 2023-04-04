<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignTasker extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'task_id',
        'tasker_id',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function tasker()
    {
        return $this->belongsTo(User::class);
    }
}
