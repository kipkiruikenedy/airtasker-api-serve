<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::where('task_id', $request->input('task_id'))
            ->with('sender', 'receiver')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'content' => $request->input('content'),
            'sender_id' => $request->input('sender_id'),
            'receiver_id' => $request->input('receiver_id'),
            'task_id' => $request->input('task_id'),
        ]);

        return response()->json($message, 201);
    }
}
