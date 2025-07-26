<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
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

        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random(16)),
            'email_verified_at' => now(),
        ]);
        $newUser->set_password = false;
        $newUser->save();

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
        $user = User::where('email', $fbUser->getEmail())->first();
        if ($user) {
            $user->update(['facebook_id' => $fbUser->getId()]);
            return $user;
        }
        $newUser = User::create([
            'name' => $fbUser->getName(),
            'email' => $fbUser->getEmail(),
            'facebook_id' => $fbUser->getId(),
            'password' => bcrypt(Str::random(16)),
            'email_verified_at' => now(),
        ]);
        $newUser->set_password = false;
        $newUser->save();

        return $newUser;
    }
}
