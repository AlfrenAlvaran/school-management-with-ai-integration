<?php

namespace App\Services;

use App\Models\User;
use Core\Security\Hash;

class UserService
{
    public function register(array $data): User
    {
        if ($this->findByEmail($data['email'])) {
            throw new \Exception("Email already exists.");
        }

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', '=', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $user = $this->findById($userId);
        if (!$user) return false;

        $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
        return $user->save();
    }

    public function deleteUser($email): bool
    {
        $user = User::query()->where('email', '=', $email)->first();
        if ($user) {
            return $user->delete();
        }
        return false;
    }


    
    public function getStudentById(string $id)
    {
        return User::where('email', '=', $id)->first();
    }

    public function getAllUsers()
    {
        return User::all();
    }
}
