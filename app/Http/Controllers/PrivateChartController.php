<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivateChart;



class PrivateChartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $receiverId = $request->input('receiver_id');

        $charts = PrivateChart::where(function ($query) use ($user, $receiverId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($user, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $user->id);
        })->orderBy('created_at', 'desc')->get();

        return response()->json($charts);
    }

    public function getChart(Request $request, $userId) {
        $authUserId = 2;
        $chart = PrivateChart::where(function($query) use ($authUserId, $userId) {
            $query->where('sender_id', $authUserId)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($authUserId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $authUserId);
        })->with('sender', 'receiver')->first();
    
        if (!$chart) {
            $chart = new PrivateChart();
            $chart->sender_id = $authUserId;
            $chart->receiver_id = $userId;
            $chart->save();
        }
    
        return response()->json($chart);
    }
    
    





    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        $chart = PrivateChart::create($validatedData);

        return response()->json($chart);
    }

    public function markAsRead(Request $request,PrivateChart $chart)
    {
        $chart->read_at = now();
        $chart->save();

        return response()->json($chart);
    }
}
