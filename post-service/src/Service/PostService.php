<?php declare(strict_types=1);

namespace PostService\Service;

use Doctrine\DBAL\Connection;
use Symfony\Component\Uid\Uuid;

class PostService implements PostServiceInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function createPost(Uuid $userId, Uuid $groupId, string $content): bool
    {
        if (!$this->canUserPostToGroup($userId, $groupId)) {
            return false;
        }
        $groupPostId = Uuid::v7();

        return $this->connection->executeStatement(
        "INSERT INTO group_post(group_post_id, group_id, created_by_id, content)
             VALUES({$groupPostId->toHex()}, {$groupId->toHex()}, {$userId->toHex()}, :content)",
            [
                'content' => $content,
            ]
        ) > 0;
    }

    public function deletePost(Uuid $userId, Uuid $postId): bool
    {
        if (!$this->canUserDeletePost($userId, $postId)) {
            return false;
        }

        return $this->connection->executeStatement(
            "DELETE FROM group_post WHERE group_post_id = {$postId->toHex()}"
        ) > 0;
    }

    public function getPostsFromGroup(Uuid $groupId): array
    {
        return $this->connection->executeQuery(
            "SELECT * FROM group_post WHERE group_id = {$groupId->toHex()}"
        )->fetchAllAssociative();
    }

    public function canUserPostToGroup(Uuid $userId, Uuid $groupId): bool
    {
        return $this->connection->executeQuery(
        "SELECT ug.user_id FROM user_group ug
            WHERE ug.user_id = {$userId->toHex()} AND ug.group_id = {$groupId->toHex()} AND ug.accepted = 1
            UNION SELECT g.created_by
            FROM `group` g WHERE g.created_by = {$userId->toHex()}"
        )->fetchOne() !== false;
    }

    public function canUserDeletePost(Uuid $userId, Uuid $postId): bool
    {
        return $this->connection->executeQuery(
                "SELECT 1 FROM group_post WHERE created_by_id = {$userId->toHex()} AND group_post_id = {$postId->toHex()}"
            )->fetchOne() !== false;
    }
}