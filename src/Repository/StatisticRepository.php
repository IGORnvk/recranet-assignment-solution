<?php

namespace App\Repository;

use App\Entity\Statistic;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistic>
 *
 * @method Statistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistic[]    findAll()
 * @method Statistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    /**
     * creates or updates new statistic based on passed info
     * @param array $info information about the team
     * @param Team $team
     * @return void
     */
    public function updateStatistic(array $info, Team $team): void
    {
        $entityManager = $this->getEntityManager();

        $statistic = $this->findOneBy(['team' => $team->getId()]) ? $this->findOneBy(['team' => $team->getId()]) : new Statistic();

        // set all the necessary values for statistic
        $statistic
            ->setTeam($team)
            ->setPlayed($info['playedGames'])
            ->setWon($info['won'])
            ->setLost($info['lost'])
            ->setDraw($info['draw'])
            ->setGoalDifference($info['goalDifference'])
            ->setPoints($info['points'])
        ;

        $entityManager->persist($statistic);
        $entityManager->flush();
    }

//    /**
//     * @return Statistic[] Returns an array of Statistic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Statistic
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
