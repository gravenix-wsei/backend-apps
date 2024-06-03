<?php declare(strict_types=1);

namespace App\Core\Service\Group;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class GroupService implements GroupServiceInterface
{
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
}