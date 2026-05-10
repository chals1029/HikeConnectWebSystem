<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfilePicture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProfilePictureDatabaseWriter
{
    /**
     * Store image bytes in user_profile_pictures, clear legacy disk path on users, delete old file.
     *
     * @throws RuntimeException When the file cannot be read.
     */
    public function storeFromUploadedFile(User $user, UploadedFile $file): void
    {
        $realPath = $file->getRealPath();
        if ($realPath === false) {
            throw new RuntimeException('Could not read the uploaded file.');
        }

        $raw = file_get_contents($realPath);
        if ($raw === false || $raw === '') {
            throw new RuntimeException('Could not read the uploaded file.');
        }

        $oldPath = $user->profile_picture_path;
        $mime = $file->getMimeType() ?: 'image/jpeg';

        UserProfilePicture::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'data' => base64_encode($raw),
                'mime' => $mime,
            ]
        );

        $user->profile_picture_path = null;
        $user->save();
        $user->unsetRelation('profilePicture');

        if ($oldPath) {
            try {
                Storage::disk('public')->delete($oldPath);
            } catch (\Throwable) {
                //
            }
        }
    }
}
