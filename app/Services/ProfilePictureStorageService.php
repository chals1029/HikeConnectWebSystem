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
        if (! $file->isValid()) {
            $msg = $file->getErrorMessage() ?: 'The uploaded file is not valid.';
            throw new RuntimeException($msg);
        }

        $publicRoot = storage_path('app/public');
        if (! is_dir($publicRoot)) {
            @mkdir($publicRoot, 0755, true);
        }
        if (! is_dir($publicRoot) || ! is_writable($publicRoot)) {
            throw new RuntimeException(
                'The server cannot write to storage/app/public. Fix ownership/permissions for the PHP user (e.g. www-data) and ensure that folder exists.'
            );
        }

        $disk = Storage::disk('public');
        $dir = 'profile-pictures/'.$user->id;
        $disk->makeDirectory($dir);
        if (! $disk->exists($dir)) {
            throw new RuntimeException('Could not create the profile-pictures folder inside storage.');
        }

        if ($user->profile_picture_path) {
            try {
                $disk->delete($user->profile_picture_path);
            } catch (\Throwable) {
                // Old file missing or unreadable — continue with new upload.
            }
        }

        try {
            $path = $file->store($dir, 'public');
        } catch (\Throwable $e) {
            Log::error('Profile picture store threw', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            throw new RuntimeException('Failed to save the file: '.$e->getMessage(), 0, $e);
        }

        if (! is_string($path) || $path === '') {
            Log::error('Profile picture store returned empty path', [
                'user_id' => $user->id,
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

        return 'Could not save your photo. On the server: make storage/app/public and bootstrap/cache writable for the PHP user, run php artisan storage:link, and set PHP upload_max_filesize and post_max_size (and nginx client_max_body_size if used) to at least 12M.';
    }
}
