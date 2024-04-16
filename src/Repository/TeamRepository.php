<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    private StatisticRepository $statisticRepository;

    private SeasonTeamRepository $seasonTeamRepository;

    public function __construct(ManagerRegistry $registry, StatisticRepository $statisticRepository, SeasonTeamRepository $seasonTeamRepository)
    {
        $this->statisticRepository = $statisticRepository;
        $this->seasonTeamRepository = $seasonTeamRepository;

        parent::__construct($registry, Team::class);
    }

    /**
     * creates or updates current state of the team
     * @param array $teamInfo information about the team
     * @param int $position team's position in the leaderboard
     * @param array $statistic statistics of the team
     * @param string $year
     * @return void
     */
    public function updateTeam(array $teamInfo, int $position, array $statistic, string $year): void
    {
        $entityManager = $this->getEntityManager();

        // create new object for team or retrieve existing
        $team = $this->findOneBy(['name' => $teamInfo['name']]) ? $this->findOneBy(['name' => $teamInfo['name']]) : new Team();

        // set all the necessary values for team
        $team
            ->setName($teamInfo['name'])
            ->setLogo($teamInfo['crest'])
        ;

        // update other information related to the team
        $this->statisticRepository->updateStatistic($statistic, $team);
        $this->seasonTeamRepository->updateSeasonTeam($year, $team, $position);

        $entityManager->persist($team);
        $entityManager->flush();
    }

//    /**
//     * @return Team[] Returns an array of Team objects
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

//    public function findOneBySomeField($value): ?Team
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
