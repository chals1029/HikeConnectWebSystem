<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfilePicture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    /**
     * Public image response: DB row first, then legacy disk path on users.
     */
    public function show(User $user)
    {
        $pic = UserProfilePicture::query()
            ->where('user_id', $user->id)
            ->select(['data', 'mime'])
            ->first();

        if ($pic) {
            $binary = base64_decode((string) $pic->data, true);
            if ($binary === false || $binary === '') {
                abort(404);
            }

            $mime = (string) ($pic->mime ?: 'image/jpeg');
            $etag = '"'.hash('sha256', $binary).'"';

            if (request()->header('If-None-Match') === $etag) {
                return response('', 304)->withHeaders([
                    'ETag' => $etag,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }

            return response($binary, 200, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=86400',
                'ETag' => $etag,
            ]);
        }

        $path = DB::table('users')->where('id', $user->id)->value('profile_picture_path');
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        abort(404);
    }
}
