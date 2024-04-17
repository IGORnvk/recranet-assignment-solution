<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

        $entityManager->persist($team);
        $entityManager->flush();

        // update other information related to the team
        $seasonTeam = $this->seasonTeamRepository->updateSeasonTeam($year, $team, $position);
        $this->statisticRepository->updateStatistic($statistic, $seasonTeam);
    }

    /**
     * updates user_team relation based on condition
     * @param User|UserInterface $user user to add or remove from team
     * @param Team $team team to manage
     * @param string $follow condition (true or false in form of string, can't pass booleans in routes)
     * @return void
     */
    public function updateUserRelation(User|UserInterface $user, Team $team, string $follow)
    {
        $entityManager = $this->getEntityManager();

        if ($follow == 'true') {
            $team->addUser($user);
        } else {
            $team->removeUser($user);
        }

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
