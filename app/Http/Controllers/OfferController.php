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
            'tasker_id'=>'required|numeric',
            'task_id'=> 'required|numeric',
            
        ]);

       // check if application already exists for the job and applicant
       $offerExists = Offer::where('task_id', $validated['task_id'])
       ->where('tasker_id', $validated['tasker_id'])
       ->exists();

   if ($offerExists) {
       return response()->json([
           'message' => 'You have already submited bid for this job',
       ], 422);
   }
    //CREATE OFFER
        $offer=Offer::create([
            'content'=>$request->content,
            'tasker_id'=>$request->tasker_id,
            'task_id'=>$request->task_id,
        ]);
    
        return response()->json([
            'task'=>$offer,
            'message'=>'congratulations!, Your bid have been submited successfully'
        ],201);   
    }
}
