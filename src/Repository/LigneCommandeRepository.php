<?php

namespace App\Repository;

use App\Entity\LigneCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method LigneCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneCommande[]    findAll()
 * @method LigneCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneCommande::class);
    }

    // /**
    //  * @return LigneCommande[] Returns an array of LigneCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneCommande
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return array
     */
    public function findTopSellProduit() : array
    {

        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQuery(
            'SELECT IDENTITY(l.produit) as p , SUM(l.quantite) qte
            FROM App\Entity\LigneCommande l
            GROUP BY l.produit
            ORDER BY qte DESC
            
            '
        )
        ->setMaxResults(4);
        return $qb->getResult();
    }
        /**
     * @return array
     */
    public function findProductForMail($commandeId) : array
    {
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\LigneCommande l
            WHERE l.commande = :commandeId
            ORDER BY l.quantite DESC
            '
        )->setParameter('commandeId',$commandeId);
        
        return $qb->getResult();
    }
}