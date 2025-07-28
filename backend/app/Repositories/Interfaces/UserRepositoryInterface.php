<?php
// app/Repositories/Interfaces/UserRepositoryInterface.php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
    public function paginate(int $perPage = 15): Paginator;
    public function findOrCreateGoogleUser($googleUser): User;
    public function save(User $user): User;
    public function updatePassword(User $user, string $hashedPassword): void;
    public function markPasswordSet(User $user): void;
}
