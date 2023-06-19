<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('userId');

        $notifications = Notification::where('user_id', $userId)->get();
        $unreadCount = $notifications->where('status', '0')->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
