<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }


    /**
     * Recherche les messages entre deux utilisateur
     * @param $id_from L'expéditeur
     * @param $id_to Le destinataire
     */
    public function findPrivateChats($id_from, $id_to)
    {
        // SELECT * FROM chat AS c WHERE (send_from_id = 17 AND send_to_id = 28) OR (send_from_id = 28 AND send_to_id = 17)
        return $this->createQueryBuilder('c')
                ->orWhere('c.send_from = :id_from AND c.send_to = :id_to')
                ->orWhere('c.send_from = :id_to AND c.send_to = :id_from')
                ->setParameter('id_from', $id_from)
                ->setParameter('id_to', $id_to)
                ->orderBy('c.created_at', 'ASC')
                //->setMaxResults(10)
                ->getQuery()
                ->getResult();

    }

    public function findContact($id_from)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT send_to_id
            FROM chat
            WHERE send_from_id = ?
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_from]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    

    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
