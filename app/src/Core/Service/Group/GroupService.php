<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
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
            $group->setGroupId(Uuid::v4());
            $group->setName($name);
            $group->setCreatedBy($user->getId());
            $group->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($group);
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
        $repository = $this->entityManager->getRepository(Group::class);
        if (!$repository instanceof GroupRepository) {
            return [];
        }

        return $repository->findUserGroups($user->getId());
    }
}