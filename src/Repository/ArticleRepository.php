<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
// ici nous déclarons une fonction attendant un //argument $price, cette fonction a pour objectif d'effectuer une requete de selection des //articles avec condition sur le prix
    public function findByPrice($price)
    {
        return $this->createQueryBuilder('a')// ici nous appelons un méthode de //repository de symfony nous permettant de customiserles méthodes de bases de select du //repository ( findAll(), find() findBy() findOneBy() ) en y ajoutant des conditions //(WHERE), des limites (setMaxResult) et d'ordonner notre résultat (OrderBy)
            ->andWhere('a.prix <= :maxprix')// ici nous déclarons notre condition paramètrée //avec le : en utlisant //l'alias du queryBuilder déclaré plus haut (en l'occurence à de //article) et appelons la propriété visée en //concaténant à.propriété (en l'occurence ici //le prix) cette condition accepte tous les critères de comparaison (=,<,>,<=,>=,!=)
            ->setParameter('maxprix', $price)// ici nous affectons la valeur de notre //paramètre maxprix avec //l'argument $price envoyé dans la fonction
            ->orderBy('a.prix', 'ASC')// ici nous précisons que la requête doit être rendue avec les prix //croissants
            ->getQuery()// getQuery prépare la requete
            ->getResult()// getResult nous renvoie le resultat.
        ;
    }



    public function findByCategoryAndPrice($cat, $price)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.prix <= :maxprix')
            ->setParameter('maxprix', $price)
            ->andWhere('a.categorie = :cat')
            ->setParameter('cat', $cat)
            ->getQuery()
            ->getResult()
        ;
    }

    // méthode permettant de récupérer la donnée 'nom' de la table patient en bdd
    public function search(string $filter)
    {
        $builder = $this->createQueryBuilder('a');

        $builder
            ->andWhere('a.nom LIKE :nom')
            ->setParameter('nom', '%'. $filter . '%')
        ;

        $query = $builder->getQuery();

        return $query->getResult();

    }

    // méthode liée à l'autocomplétion de la barre de recherche

    public function autocomplete($term)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a.nom, a.description')
            ->where('a.nom LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orWhere('a.description LIKE :desc')
            ->setParameter('desc', '%' . $term . '%');

        $arrayAss = $qb->getQuery()
            ->getResult();

        $array = array();

        // le résultat de la requête est bouclé afin d'effectuer la recherche sur chaque ligne de la table patient
        foreach ($arrayAss as $data) {

            $array[] = $data['nom'];
        }

        return $array;
    }
}
