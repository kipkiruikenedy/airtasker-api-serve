<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Task;

class OfferController extends Controller
{

    public function findOfferByID(Request $request, $id)
    {
        try {
            // Find the offer by ID
            $offer = Offer::findOrFail($id);
    
            // Return success response
            return response()->json([
                'offer' => $offer,
                'message' => 'Offer found successfully'
            ], 200);
    
        } catch (\Throwable $th) {
            // Return error response
            return response()->json([
                'message' => "Sorry, something went wrong while searching for the offer. Please try again later."
            ], 500);
        }
    }
    



    public function Offers()
    {
        $offers = Offer::all();
        return response()->json( $offers, 200);
    }



    public function createOffer(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'tasker_id' => 'required|numeric',
            'task_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
    
        // Check if application already exists for the job and applicant
        $offerExists = Offer::where('task_id', $validated['task_id'])
            ->where('tasker_id', $validated['tasker_id'])
            ->exists();
    
        if ($offerExists) {
            return response()->json([
                'message' => 'You have already submitted a bid for this job',
            ], 422);
        }
    
        // Fetch the task from the database
        $task = Task::findOrFail($validated['task_id']);
    
        // Calculate 70% of the task amount
        $minimumPrice = $task->amount * 0.7;
    
        // Check if the offer price is less than the minimum price
        if ($validated['price'] < $minimumPrice) {
            return response()->json([
                'message' => 'Your bid amount is lower than 70% of the task amount, your bid should be above $'.$minimumPrice,
            ], 422);
        }
    
        $offer = Offer::create([
            'content' => $request->content,
            'tasker_id' => $request->tasker_id,
            'task_id' => $request->task_id,
            'price' => $request->price,
        ]);
    
        // Return the offer details along with the tasker details and task details
        return response()->json([
            'offer' => $offer,
            'message' => 'Congratulations! Your bid has been submitted successfully.',
        ], 201);
    }
    
    
}
