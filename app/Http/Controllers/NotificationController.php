<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Services\NotificationDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Bell dropdown payload: 5 most recent notifications + unread total.
     */
    public function bell()
    {
        $userId = Auth::id();
        $items = NotificationDispatcher::bellFor($userId, 5);

        return response()->json([
            'success' => true,
            'unread_count' => NotificationDispatcher::unreadCountFor($userId),
            'items' => $items->map(fn (UserNotification $n) => $this->serialize($n))->all(),
        ]);
    }

    /**
     * Full history list (paginated lightly so even years of activity stays fast).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = (int) $request->integer('per_page', 30);
        $perPage = max(10, min($perPage, 100));

        $paginator = UserNotification::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'unread_count' => NotificationDispatcher::unreadCountFor($userId),
            'items' => collect($paginator->items())
                ->map(fn (UserNotification $n) => $this->serialize($n))
                ->all(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more' => $paginator->hasMorePages(),
            ],
        ]);
    }

    public function markRead(UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if ($notification->read_at === null) {
            $notification->forceFill(['read_at' => now()])->save();
        }

        return response()->json([
            'success' => true,
            'unread_count' => NotificationDispatcher::unreadCountFor(Auth::id()),
        ]);
    }

    public function markAllRead()
    {
        UserNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    private function serialize(UserNotification $n): array
    {
        return [
            'id' => $n->id,
            'type' => $n->type,
            'title' => $n->title,
            'body' => $n->body,
            'icon' => $n->icon ?: 'lucide:bell',
            'link' => $n->link,
            'is_unread' => $n->isUnread(),
            'created_at' => optional($n->created_at)->toIso8601String(),
            'created_at_human' => optional($n->created_at)->diffForHumans(),
        ];
    }
}
