<?php declare(strict_types=1);

namespace App\Core\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;

interface UserServiceInterface
{

    /**
     * @return User[]
     */
    public function searchUsers(array $userSearchCriteria): array;
}