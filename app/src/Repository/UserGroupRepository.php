<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<UserGroup>
 *
 * @method UserGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroup[]    findAll()
 * @method UserGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }

    public function findInvitesForUser(User $user): array
    {
        return $this->createQueryBuilder('ug')
            ->select('ug, u, g')
            ->leftJoin(User::class, 'u', Join::WITH, 'u = ug.user')
            ->leftJoin(Group::class, 'g', Join::WITH, 'g = ug.group')
            ->where('ug.userId = :userId')
            ->andWhere('ug.accepted = 0')
            ->setParameter('userId', $user->getId()->toBinary())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Group[]
     */
    public function findAcceptedInvitesGroups(Uuid $userId): array
    {
        return $this->createQueryBuilder('ug')
            ->select('g')
            ->leftJoin(Group::class, 'g', Join::WITH, 'ug.group = g')
            ->where('ug.accepted = 1')
            ->andWhere('ug.userId = :userId')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return UserGroup[] Returns an array of UserGroup objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserGroup
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
