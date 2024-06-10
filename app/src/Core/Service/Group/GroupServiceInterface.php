<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Repository\GroupRepository;
use Symfony\Component\Uid\Uuid;

interface GroupServiceInterface
{
    public function createGroup(string $name, User $user): bool;

    /**
     * @return Group[]
     */
    public function searchGroup(array $groupSearchCriteria): array;

    /**
     * @return User[]
     */
    public function getUserGroups(User $user): array;

    public function canDeleteGroup(Uuid $groupId, Uuid $userId): bool;

    public function deleteGroup(Uuid $groupId): bool;

    public function removeUser(Uuid $groupId, Uuid $userId): bool;

    public function inviteUser(Uuid $groupId, Uuid $userId): bool;

    /**
     * @return UserGroup[]
     */
    public function getInvitesForUser(User $user): array;

    public function acceptInvitationAsUser(Uuid $invitationId, User $user): bool;
}