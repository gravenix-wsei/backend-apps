<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @return Group[]
     */
    public function findGroupsByTerm(string $term, int $limit = 10, int $page = 1): array
    {
        return $this->createQueryBuilder('g')
            ->where('LOWER(g.name) LIKE LOWER(:term)')
            ->setParameter('term', \sprintf('%%%s%%', $term))
            ->setMaxResults($limit)
            ->setFirstResult(($page-1)*$limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Group[]
     */
    public function findUserGroups(Uuid $userId): array
    {
        // TODO add groups from invitations
        return $this->createQueryBuilder('g')
            ->where('g.createdBy = :userId')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getResult();
    }
}
