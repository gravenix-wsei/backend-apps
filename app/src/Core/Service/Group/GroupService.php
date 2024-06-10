<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Repository\GroupRepository;
use App\Repository\UserGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class GroupService implements GroupServiceInterface
{
    private const DEFAULT_LIMIT = 10;

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function createGroup(string $name, User $user): bool
    {
        try {
            $group = new Group();
            $group->setName($name);
            $group->setCreatedBy($user->getId());
            $group->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($group);
            $user->addAdminForGroup($group->getGroupId());
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * @return Group[]
     */
    public function searchGroup(array $groupSearchCriteria): array
    {
        $term = $groupSearchCriteria['term'] ?? null;
        $limit = $groupSearchCriteria['limit'] ?? self::DEFAULT_LIMIT;
        $page = $groupSearchCriteria['page'] ?? 1;
        $repository = $this->entityManager->getRepository(Group::class);
        if (!$repository instanceof GroupRepository) {
            return [];
        }

        $entities = $repository->findGroupsByTerm($term, $limit, $page);
        
        if (!empty($entities) && !$entities[0] instanceof Group) {
            throw new \RuntimeException('Unexpected type');
        }

        return $entities;
    }

    /**
     * @return User[]
     */
    public function getUserGroups(User $user): array
    {
        $groupRepository = $this->entityManager->getRepository(Group::class);
        if (!$groupRepository instanceof GroupRepository) {
            return [];
        }
        $userGroupRepository = $this->entityManager->getRepository(UserGroup::class);
        if (!$userGroupRepository instanceof UserGroupRepository) {
            return [];
        }

        return \array_merge(
            $groupRepository->findUserGroups($user->getId()),
            $userGroupRepository->findAcceptedInvitesGroups($user->getId())
        );
    }

    public function canDeleteGroup(Uuid $groupId, Uuid $userId): bool
    {
        $repository = $this->entityManager->getRepository(Group::class);
        if (!$repository instanceof GroupRepository) {
            return false;
        }

        return $repository->isUserAllowedToDeleteGroup($groupId, $userId);
    }

    public function deleteGroup(Uuid $groupId): bool
    {
        $repository = $this->entityManager->getRepository(Group::class);
        if (!$repository instanceof GroupRepository) {
            return false;
        }

        $group = $repository->find($groupId);
        if (!$group instanceof Group) {
            return false;
        }

        try {
            $this->entityManager->remove($group);
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    public function inviteUser(Uuid $groupId, Uuid $userId): bool
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->find($userId);
            $group = $this->entityManager->getRepository(Group::class)->find($groupId);

            $userGroup = new UserGroup();
            $userGroup->setUser($user);
            $userGroup->setGroup($group);
            $userGroup->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($userGroup);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }

    public function removeUser(Uuid $groupId, Uuid $userId): bool
    {
        try {
            $repository = $this->entityManager->getRepository(UserGroup::class);
            $user = $this->entityManager->getRepository(User::class)->find($userId);
            $group = $this->entityManager->getRepository(Group::class)->find($groupId);

            $userGroup = $repository->findOneBy([
                'user' => $user,
                'group' => $group,
            ]);
            if (!$userGroup instanceof UserGroup) {
                return false;
            }

            $this->entityManager->remove($userGroup);
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * @return UserGroup[]
     */
    public function getInvitesForUser(User $user): array
    {
        try {
            $repository = $this->entityManager->getRepository(UserGroup::class);
            if (!$repository instanceof UserGroupRepository) {
                return [];
            }

            return \array_filter(
                $repository->findInvitesForUser($user),
                static fn($obj) => $obj instanceof UserGroup
            );
        } catch (\Throwable) {
            return [];
        }
    }

    public function acceptInvitationAsUser(Uuid $invitationId, User $user): bool
    {
        try {
            $invitation = $this->entityManager->find(UserGroup::class, $invitationId);
            if (!$invitation || ($invitation && $invitation->getUser()->getId() !== $user->getId())) {
                return false;
            }

            $invitation->setIsAccepted(true);
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }
}