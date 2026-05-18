<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        $user = Auth::user();
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['notifications' => []]);
            }
            return redirect()->route('login');
        }

        $notificationsQuery = $user->notifications()
            ->select('id', 'title', 'message', 'type', 'link', 'image', 'read_at', 'created_at')
            ->orderBy('created_at', 'desc');

        if ($request->ajax() || $request->wantsJson()) {
            $notifications = $notificationsQuery->limit(20)->get();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications()->count(),
            ]);
        }

        $notifications = $notificationsQuery->get();

        return view('notifications', compact('notifications'));
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
