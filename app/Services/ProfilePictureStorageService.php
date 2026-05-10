<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProfilePictureStorageService
{
    /**
     * Store a new profile image on the public disk and return the relative path.
     *
     * @throws RuntimeException When the directory is not writable or store() fails.
     */
    public function storeForUser(User $user, UploadedFile $file): string
    {
        $disk = Storage::disk('public');
        $dir = 'profile-pictures/'.$user->id;
        $disk->makeDirectory($dir);

        if ($user->profile_picture_path) {
            try {
                $disk->delete($user->profile_picture_path);
            } catch (\Throwable) {
                // Old file missing or unreadable — continue with new upload.
            }
        }

        $path = $file->store($dir, 'public');

        if (! is_string($path) || $path === '') {
            Log::error('Profile picture store returned empty path', [
                'user_id' => $user->id,
                'disk_root' => $disk->path(''),
            ]);

            throw new RuntimeException('Upload could not be saved (storage returned no path).');
        }

        return $path;
    }

    /**
     * User-visible message when upload/storage fails (VPS: permissions, storage:link, PHP limits).
     */
    public static function clientMessageForException(\Throwable $e): string
    {
        if (config('app.debug')) {
            return $e->getMessage();
        }

        return 'Could not save your photo. On the server: allow writes on storage/ and bootstrap/cache, run php artisan storage:link, and set PHP upload_max_filesize and post_max_size to at least 8M (nginx: client_max_body_size).';
    }
}
