<?php

namespace App\Repository;

use App\Entity\SeasonTeam;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeasonTeam>
 *
 * @method SeasonTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonTeam[]    findAll()
 * @method SeasonTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonTeamRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private SeasonRepository $seasonRepository)
    {
        parent::__construct($registry, SeasonTeam::class);
    }

    /**
     * creates or updates season_team values
     * @param string $year year of the season to look for
     * @param Team $team team to look for
     * @param int $position position in the leaderboard of the specified team
     * @return SeasonTeam|null
     */
    public function updateSeasonTeam(string $year, Team $team, int $position): ?SeasonTeam
    {
        $entityManager = $this->getEntityManager();

        // retrieve season
        $this->seasonRepository->insertSeasons([$year]);
        $season = $this->seasonRepository->findOneBy(['year' => $year]);

        // retrieve seasonTeam or create a new one
        $seasonTeam = $this->findOneBy(['team' => $team->getId(), 'season' => $season->getId()]) ?
            $this->findOneBy(['team' => $team->getId(), 'season' => $season->getId()]) : new SeasonTeam();

        // set values
        $seasonTeam
            ->setTeam($team)
            ->setSeason($season)
            ->setPosition($position)
        ;

        $entityManager->persist($seasonTeam);
        $entityManager->flush();

        return $seasonTeam;
    }

//    /**
//     * @return SeasonTeam[] Returns an array of SeasonTeam objects
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

//    public function findOneBySomeField($value): ?SeasonTeam
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
