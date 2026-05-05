<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower($email)]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function create(string $name, string $email, string $password): array
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())'
        );

        $statement->execute([
            'name' => $name,
            'email' => strtolower($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return $this->findByEmail($email);
    }
}
