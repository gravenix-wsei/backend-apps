<?php declare(strict_types=1);

namespace App\Core\Service\Post;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface PostServiceInterface
{
    public function createPost(User $user, Group $group, string $content): bool;

    public function deletePost(Uuid $userId, Uuid $postId): bool;

    public function getPosts(Uuid $userId, Uuid $groupId): array;
}