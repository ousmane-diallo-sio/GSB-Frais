<?php

namespace App\Repository;

use App\Entity\Fichefrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fichefrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichefrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichefrais[]    findAll()
 * @method Fichefrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichefraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichefrais::class);
    }


    public function supprimerFiche($idvisiteur, $mois){
        print_r("Repo : " . $mois . $idvisiteur);
        $em = $this->getEntityManager();
        $dql = "delete App\Entity\Fichefrais p where p.mois = :mois and p.idvisiteur = :idvisiteur";
        $requete = $em->createQuery($dql)
            ->setParameters(
                array(
                    'idvisiteur' => $idvisiteur,
                    'mois' => $mois
                )
                );
        $requete->execute();

        /*return $this->createQueryBuilder('f')
            ->delete()
            ->where('mois = :mois')
            ->setParameter('mois', $mois)
            ->andWhere('idvisiteur = :idvisiteur')
            ->setParameter('idvisiteur', $idvisiteur)
            ->getQuery()
            ->execute();*/
    }

    // /**
    //  * @return Fichefrais[] Returns an array of Fichefrais objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fichefrais
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
