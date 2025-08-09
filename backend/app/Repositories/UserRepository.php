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
        // Get avatar URL from Google
        $avatarUrl = $googleUser->avatar;

        // Fetch the image content
        $imageContent = Http::get($avatarUrl)->body();

        // Create an UploadedFile instance from the image content
        $file = new UploadedFile(
            tmpfile(), // Create a temporary file in PHP's memory
            'avatar_' . uniqid(),
            'image/jpeg', // Specify the MIME type
            null, // You can add the file size if known
            true // Indicate that it's a real file
        );

        // Write the image content to the temporary file
        file_put_contents($file->getPathname(), $imageContent);


        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update(['google_id' => $googleUser->getId()]);
            return $user;
        }

        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random()),
            'email_verified_at' => now(),
        ]);
        $newUser->set_password = false;
        $newUser->save();

        $this->fileService->storeFile($file, 'avatars', $newUser, 'avatar', 'public');

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
        // Get the avatar URL from Facebook's user object (usually, Facebook provides the image URL)
        $avatarUrl = "https://graph.facebook.com/{$fbUser->getId()}/picture?type=large"; // Facebook Profile Image URL

        // Fetch the image content
        $imageContent = Http::get($avatarUrl)->body();

        // Create an UploadedFile instance from the image content
        $file = new UploadedFile(
            tmpfile(), // Create a temporary file in PHP's memory
            'avatar_' . uniqid(),
            'image/jpeg', // Specify the MIME type (Assume the profile picture is in JPEG format)
            null, // You can add the file size if known
            true // Indicate that it's a real file
        );

        // Write the image content to the temporary file
        file_put_contents($file->getPathname(), $imageContent);

        // Find the user based on email
        $user = User::where('email', $fbUser->getEmail())->first();

        if ($user) {
            // Update the existing user's Facebook ID
            $user->update(['facebook_id' => $fbUser->getId()]);
        } else {
            // Create a new user if not found
            $newUser = User::create([
                'name' => $fbUser->getName(),
                'email' => $fbUser->getEmail(),
                'facebook_id' => $fbUser->getId(),
                'password' => bcrypt(Str::random(16)),  // Random password
                'email_verified_at' => now(),
            ]);
            $newUser->set_password = false;  // Set this if you don't want to let the user set a password
            $newUser->save();

            // Call the fileService to store the avatar image
            $this->fileService->storeFile($file, 'avatars', $newUser, 'avatar', 'public');

            return $newUser;
        }

        return $user;
    }

}
