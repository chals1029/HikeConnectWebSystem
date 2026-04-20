<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Record an audit log entry.
     *
     * @param  string                                 $action         Short slug, e.g. "user.login"
     * @param  string                                 $description    Human-readable description
     * @param  User|int|null                          $subject        The user the log is *about* (defaults to current user)
     * @param  Model|null                             $entity         Related model (booking, review, etc.)
     * @param  array<string, mixed>                   $meta           Extra structured data
     * @param  User|int|null                          $actor          The user performing the action (defaults to current user)
     */
    public static function log(
        string $action,
        string $description,
        $subject = null,
        ?Model $entity = null,
        array $meta = [],
        $actor = null,
    ): AuditLog {
        $current = Auth::user();
        $actorId = self::resolveId($actor) ?? self::resolveId($current);
        $userId = self::resolveId($subject) ?? $actorId;

        $request = null;
        try { $request = Request::instance(); } catch (\Throwable $e) { $request = null; }

        return AuditLog::create([
            'user_id' => $userId,
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entity ? class_basename($entity) : null,
            'entity_id' => $entity?->getKey(),
            'description' => mb_substr($description, 0, 500),
            'meta' => $meta ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? mb_substr((string) $request->userAgent(), 0, 500) : null,
        ]);
    }

    private static function resolveId($value): ?int
    {
        if ($value instanceof User) return $value->id;
        if (is_int($value)) return $value;
        if (is_numeric($value)) return (int) $value;
        return null;
    }
}
