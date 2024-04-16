<?php

namespace App\Repository;

use App\Entity\Referee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Referee>
 *
 * @method Referee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referee[]    findAll()
 * @method Referee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefereeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referee::class);
    }

    /**
     * creates or updates referee in the database
     * @param array $refereeInfo information about referee
     * @return Referee
     */
    public function updateReferee(array $refereeInfo): Referee
    {
        $entityManager = $this->getEntityManager();

        // create new object for referee or retrieve existing one
        $referee = $this->findOneBy(['name' => $refereeInfo['name']]) ?
            $this->findOneBy(['name' => $refereeInfo['name']]) :
            new Referee();

        $referee
            ->setName($refereeInfo['name'])
            ->setNationality($refereeInfo['nationality'])
        ;

        $entityManager->persist($referee);
        $entityManager->flush();

        return $referee;
    }

//    /**
//     * @return Referee[] Returns an array of Referee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Referee
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
