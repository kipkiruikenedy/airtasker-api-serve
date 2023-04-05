<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
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

    // Fetch the tasker details using the tasker_id
    $offer = new Offer([
        'content' => $request->content,
        'tasker_id' => $request->tasker_id,
        'task_id' => $request->task_id,
    ]);
    $offer->tasker = $offer->tasker()->first();
    $offer->task = $offer->task()->first();

    // Return the offer details along with the tasker details and task details
    return response()->json([
        'offer' => $offer,
        'message' => 'Congratulations! Your bid has been submitted successfully.',
    ], 201);
}

    
}
