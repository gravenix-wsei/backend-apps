<?php declare(strict_types=1);

namespace App\Core\Service\Post;

use App\Entity\Group;
use App\Entity\User;

interface PostServiceInterface
{

    public function createPost(User $user, Group $group, string $content): bool;
}