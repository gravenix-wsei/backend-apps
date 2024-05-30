<?php declare(strict_types=1);

namespace App\Core\Service\User;

use App\Entity\User;

class UserJwtService implements UserLoginServiceInterface
{
    public function loginUser(string $username, string $password): User
    {

    }
}