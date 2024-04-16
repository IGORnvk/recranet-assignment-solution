<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 *
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    /**
     * creates or updates new score based on passed info
     * @param array $scoreInfo information about score
     * @param Game $game game object to pair with
     * @return void
     */
    public function updateScore(array $scoreInfo, Game $game) {
        $entityManager = $this->getEntityManager();

        $score = $this->findOneBy(['game' => $game->getId()]) ? $this->findOneBy(['game' => $game->getId()]) : new Score();

        $score
            ->setGame($game)
            ->setHomeScore($scoreInfo['home'])
            ->setGuestScore($scoreInfo['away'])
        ;

        $entityManager->persist($score);
        $entityManager->flush();
    }

//    /**
//     * @return Score[] Returns an array of Score objects
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

//    public function findOneBySomeField($value): ?Score
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
