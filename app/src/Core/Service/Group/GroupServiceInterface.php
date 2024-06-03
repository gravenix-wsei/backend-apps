<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;

interface GroupServiceInterface
{
    public function createGroup(string $name, User $user): bool;
}