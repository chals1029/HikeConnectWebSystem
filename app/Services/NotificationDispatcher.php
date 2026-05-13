<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Lightweight in-app notification dispatcher.
 *
 * Notifications are persisted to user_notifications and surfaced via the
 * shared bell partial in every dashboard sidebar plus a dedicated history
 * tab. Failures are logged but never bubble up — emitting a notification
 * must not break the operation that triggered it (booking approval, SOS,
 * mountain safety change, etc.).
 */
class NotificationDispatcher
{
    /**
     * Record a notification for a specific user. Returns true on success.
     */
    public static function notify(int|User|null $user, string $type, string $title, ?string $body = null, ?string $link = null, ?string $icon = null, array $meta = []): bool
    {
        $userId = $user instanceof User ? $user->id : (int) $user;
        if (! $userId) {
            return false;
        }

        try {
            if (! Schema::hasTable('user_notifications')) {
                return false;
            }

            UserNotification::create([
                'user_id' => $userId,
                'type' => substr($type, 0, 64),
                'title' => substr($title, 0, 255),
                'body' => $body !== null ? substr($body, 0, 1000) : null,
                'icon' => $icon !== null ? substr($icon, 0, 64) : null,
                'link' => $link !== null ? substr($link, 0, 255) : null,
                'meta' => $meta ?: null,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::warning('Notification dispatch failed', [
                'user_id' => $userId,
                'type' => $type,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Notify every admin user. Useful for system-wide events (SOS, etc.).
     */
    public static function notifyAdmins(string $type, string $title, ?string $body = null, ?string $link = null, ?string $icon = null, array $meta = []): int
    {
        $count = 0;
        try {
            $admins = User::query()->where('role', User::ROLE_ADMIN)->pluck('id');
            foreach ($admins as $adminId) {
                if (self::notify($adminId, $type, $title, $body, $link, $icon, $meta)) {
                    $count++;
                }
            }
        } catch (Throwable $e) {
            Log::warning('Admin notification dispatch failed', [
                'type' => $type,
                'message' => $e->getMessage(),
            ]);
        }

        return $count;
    }

    /**
     * Recent notifications for the bell dropdown — capped to a small number.
     */
    public static function bellFor(int $userId, int $limit = 5): Collection
    {
        if (! Schema::hasTable('user_notifications')) {
            return collect();
        }

        return UserNotification::query()
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public static function unreadCountFor(int $userId): int
    {
        if (! Schema::hasTable('user_notifications')) {
            return 0;
        }

        return UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
