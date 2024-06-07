<?php declare(strict_types=1);

namespace App\Core\Service\User;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserFriend;
use App\Repository\UserFriendRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class UserService implements UserServiceInterface
{
    public const DEFAULT_LIMIT = 10;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @return User[]
     */
    public function searchUsers(array $userSearchCriteria): array
    {
        $term = $userSearchCriteria['term'] ?? null;
        $limit = $userSearchCriteria['limit'] ?? self::DEFAULT_LIMIT;
        $page = $userSearchCriteria['page'] ?? 1;
        $repository = $this->entityManager->getRepository(User::class);
        if (!$repository instanceof UserRepository) {
            return [];
        }

        $entities = $repository->searchUsers($term, $limit, $page);

        if (!empty($entities) && !$entities[0] instanceof User) {
            throw new \RuntimeException('Unexpected type');
        }

        return $entities;
    }

    public function inviteUser(Uuid $invitingId, Uuid $invitedId): bool
    {
        try {
            $userRepository = $this->entityManager->getRepository(User::class);
            $friendRequest = new UserFriend();
            $friendRequest->setUser($userRepository->find($invitingId));
            $friendRequest->setFriend($userRepository->find($invitedId));
            $friendRequest->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($friendRequest);
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    public function acceptInvite(Uuid $userId, Uuid $acceptedId): bool
    {
        try {
            $repository = $this->entityManager->getRepository(UserFriend::class);
            if (!$repository instanceof UserFriendRepository) {
                return false;
            }
            $friendRequest = $repository->findUserInvitation($userId, $acceptedId);
            if (!$friendRequest) {
                return false;
            }

            $friendRequest->setAccepted(true);
            $this->entityManager->flush();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * @return UserFriend[]
     */
    public function listUserFriendRequests(Uuid $userId): array
    {
        try {
            $repository = $this->entityManager->getRepository(UserFriend::class);
            if (!$repository instanceof UserFriendRepository) {
                return [];
            }

            return $repository->getFriendRequestsFor($userId);
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @return User[]
     */
    public function listUserFriend(Uuid $userId): array
    {
        try {
            $repository = $this->entityManager->getRepository(UserFriend::class);
            if (!$repository instanceof UserFriendRepository) {
                return [];
            }

            $userFriends = $repository->getUserFriendsFor($userId);

            return \array_values(\array_filter(\array_map(
                static function($userFriend) use ($userId) {
                    if (!$userFriend instanceof UserFriend) {
                        return null;
                    }

                    return $userFriend->getUserId()->toHex() === $userId->toHex() ?
                        $userFriend->getFriend() : $userFriend->getUser();
                },
                $userFriends
            )));
        } catch (\Throwable) {
            return [];
        }
    }
}