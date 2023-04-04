<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class RatingController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        
        // Find the tutor by ID
        $tasker = User::findOrFail($id);
        
        // Create a new rating for the tutor
        $rating = new Rating();
        $rating->rating = $request->input('rating');
        $rating->comment = $request->input('comment');
        
        // Associate the rating with the tutor
        $tasker->ratings()->save($rating);
        
        return response()->json(['message' => 'Rating submitted successfully']);
    }
}
