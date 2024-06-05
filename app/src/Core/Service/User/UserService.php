<?php declare(strict_types=1);

namespace App\Core\Service\User;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
}