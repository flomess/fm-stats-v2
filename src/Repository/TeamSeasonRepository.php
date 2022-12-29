<?php

namespace App\Repository;

use App\Entity\TeamSeason;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamSeason>
 *
 * @method TeamSeason|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamSeason|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamSeason[]    findAll()
 * @method TeamSeason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamSeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamSeason::class);
    }

    public function add(TeamSeason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TeamSeason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getYears(Team $team)
    {
        $lastSeason = $this->createQueryBuilder('t')
            ->where('t.team = :team')
            ->setParameter('team', $team)
            ->orderBy('t.name', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $lastYear = substr($lastSeason[0]->getName(), '-4');

        // make array of all previous years
        $years[$lastYear + 1] = $lastYear + 1;
        while ($lastYear > 2021) {
            $years[$lastYear] = $lastYear;
            $lastYear--;
        }

        return array_reverse($years, true);
    }

    //    /**
    //     * @return TeamSeason[] Returns an array of TeamSeason objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TeamSeason
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}