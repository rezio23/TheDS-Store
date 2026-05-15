<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['notifications' => []]);
        }

        $notifications = $user->notifications()
            ->select('id', 'title', 'message', 'type', 'link', 'read_at', 'created_at')
            ->limit(20)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(int $id): JsonResponse
    {
        $user = Auth::user();
        $notification = $user?->notifications()->find($id);

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}
