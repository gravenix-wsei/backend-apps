<?php declare(strict_types=1);

namespace App\Core\Service\User;

use App\Entity\User;
use App\Entity\UserFriend;
use App\Repository\UserFriendRepository;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

interface UserServiceInterface
{

    /**
     * @return User[]
     */
    public function searchUsers(array $userSearchCriteria): array;

    public function inviteUser(Uuid $invitingId, Uuid $invitedId): bool;

    public function acceptUser(Uuid $userId, Uuid $acceptedId): bool;

    /**
     * @return UserFriend[]
     */
    public function listUserFriendRequests(Uuid $userId): array;
}