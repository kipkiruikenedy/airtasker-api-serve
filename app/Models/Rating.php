<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'rating',
        'comment',
    ];

    public function tasker()
    {
        return $this->belongsTo(User::class);
    }
}
