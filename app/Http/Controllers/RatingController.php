<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rating;


class RatingController extends Controller
{

    public function store(Request $request)
    {

        $validated = $request->validate([
            'rating' => 'integer|min:1|max:5',
            'comment' => 'nullable|string',
            'user_id'=>'numeric',
            'task_id'=>'numeric',
           
            
        ]);
    
    
        $rating=Rating::create([
            'rating'=>$request->rating,
            'task_id'=>$request->task_id,
            'comment'=>$request->comment,
            'user_id'=>$request->user_id,
        
        ]);
    
        return response()->json([
            'rating'=>$rating,
            'message'=>'rating added successfully'
        ],201);   
    }



    public function getTaskerRating($id)
{
    // Get all the ratings for the tasker with the given id
    $ratings = Rating::where('user_id', $id)->get();

    // Calculate the total number of stars and the number of times the user has been rated
    $totalStars = 0;
    $timesRated = $ratings->count();
    foreach ($ratings as $rating) {
        $totalStars += $rating->rating;
    }

    // Calculate the average rating
    $averageRating = ($timesRated > 0) ? ($totalStars / $timesRated) : 0;

    return response()->json([
        'times_rated' => $timesRated,
        'total_stars' => $totalStars,
        'average_rating' => $averageRating
    ], 200);
}
}
