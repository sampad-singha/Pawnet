<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\File\FileService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function all(): Collection
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        return User::destroy($id);
    }

    public function paginate(int $perPage = 15): Paginator
    {
        return User::paginate($perPage);
    }

    public function findOrCreateGoogleUser($googleUser): User
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update(['google_id' => $googleUser->getId()]);
            return $user;
        }
        $avatarUrl = $googleUser->avatar;
        $imageContent = Http::get($avatarUrl)->body();
        $tempFilePath = storage_path('app/public/temp/avatar_' . uniqid());

        $tempDir = storage_path('app/public/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        File::put($tempFilePath, $imageContent);

        // Create an instance of UploadedFile from the temporary file
        $file = new UploadedFile($tempFilePath, 'avatar_' . uniqid(), null, null, true);

        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random()),
            'email_verified_at' => now(),
        ]);
        $newUser->set_password = false;
        $newUser->save();

        $this->fileService->storeFile($file, 'avatars', $newUser, 'avatar');


        // Optionally, delete the temporary file after use
        File::delete($tempFilePath);

        return $newUser;
    }

    public function save(User $user): User
    {
        $user->save();
        return $user;
    }

    public function updatePassword(User $user, string $hashedPassword): void
    {
        $user->password = $hashedPassword;
        $this->save($user);
    }

    public function markPasswordSet(User $user): void
    {
        $user->set_password = true;
        $this->save($user);
    }

    public function findOrCreateFacebookUser($fbUser): User
    {
        // Find or create the user based on email
        $user = User::where('email', $fbUser->getEmail())->first();

        if ($user) {
            // Update the existing user's Facebook ID
            $user->update(['facebook_id' => $fbUser->getId()]);
        } else {
            // Get the avatar URL from Facebook's user object (using Facebook Graph API)
            $avatarUrl =  $fbUser->avatar; // Facebook Profile Image URL

            // Fetch the image content from the URL
            $imageContent = Http::get($avatarUrl)->body();

            // Define a temporary file path (ensure the directory exists)
            $tempFilePath = storage_path('app/public/temp/avatar_' . uniqid() . '.jpg'); // Using a unique name for the temporary file

            // Ensure the temp directory exists
            $tempDir = storage_path('app/public/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true); // Create directory if it doesn't exist
            }

            // Write the image content to the temporary file
            File::put($tempFilePath, $imageContent);

            // Create an instance of UploadedFile from the temporary file path
            $file = new UploadedFile(
                $tempFilePath,               // Path to the temporary file
                'avatar_' . uniqid() . '.jpg', // Original name (or generate a unique name)
                'image/jpeg',                 // MIME type (assumed to be JPEG)
                null,                         // You can add the file size if known
                true                          // Indicate that it's a real file
            );

            // Create a new user
            $newUser = User::create([
                'name' => $fbUser->getName(),
                'email' => $fbUser->getEmail(),
                'facebook_id' => $fbUser->getId(),
                'password' => bcrypt(Str::random(16)),  // Random password
                'email_verified_at' => now(),
            ]);
            $newUser->set_password = false;
            $newUser->save();

            // Store the file (avatar) using the fileService's storeFile method
            $this->fileService->storeFile($file, 'avatars', $newUser, 'avatar');

            // Optionally, delete the temporary file after the upload is done
            File::delete($tempFilePath);

            return $newUser;
        }

        return $user;
    }

}
