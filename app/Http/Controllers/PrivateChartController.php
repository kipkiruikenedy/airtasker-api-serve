<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivateChart;

class PrivateChartController extends Controller
{
    public function getUsers(Request $request, $id)
    {
        $users = PrivateChart::where('sender_id', $id)
            ->orWhere('receiver_id', $id)
            ->with('sender', 'receiver')
            ->get()
            ->unique('sender_id')
            ->map(function ($chat) use ($id) {
                return $chat->sender_id == $id ? $chat->receiver : $chat->sender;
            });

        return response()->json($users);
    }




    public function getMessages(Request $request)
    {
        $sender_id = $request->query('sender_id');
        $receiver_id = $request->query('receiver_id');
        $task_id = $request->query('task_id');

        $messages = PrivateChart::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
        })->where('task_id', $task_id)->get();

        return response()->json($messages);
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

    public function markAsRead(Request $request, $chart)
    {
        $chart = PrivateChart::findOrFail($chart);
        $chart->read_at = now();
        $chart->save();

        return response()->json($chart);
    }
}
