<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    public function findByChildIds(array $value, int $page, ?string $sort_method)
    {
        if($sort_method != 'rating')
        {
        $dbquery = $this->createQueryBuilder('p')           
                    ->andWhere('p.category IN (:val)')
                    ->leftJoin('p.comments', 'c')
                    ->leftJoin('p.usersThatLike', 'l')
                    ->leftJoin('p.usersThatDontLike', 'd')
                    ->addSelect('c','l','d') //Eager loading to reduce queries
                    ->setParameter('val', $value)
                    ->orderBy('p.title', $sort_method);
        }
        else
        {
            $dbquery =  $this->createQueryBuilder('p')
            ->addSelect('COUNT(l) AS HIDDEN likes') //
            ->leftJoin('p.usersThatLike', 'l')
            ->andWhere('p.category IN (:val)')
            ->setParameter('val', $value)
            ->groupBy('p')
            ->orderBy('likes', 'DESC');
        }

        $dbquery->getQuery();
        

        $pagination = $this->paginator->paginate($dbquery, $page, 3);
        return $pagination;
    }

    public function findByTitle(string $query, int $page, ?string $sort_method)
    {
        // $sort_method = $sort_method != 'rating' ? $sort_method : 'ASC'; // tmp

        $querybuilder = $this->createQueryBuilder('p');
        $searchTerms = $this->prepareQuery($query);

        foreach ($searchTerms as $key => $term)
        {
            $querybuilder
                ->orWhere('p.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.trim($term).'%'); 
        }

        if($sort_method != 'rating')
        {
            $dbquery =  $querybuilder
                ->orderBy('p.title', $sort_method)
                ->leftJoin('p.comments', 'c')
                ->leftJoin('p.usersThatLike', 'l')
                ->leftJoin('p.usersThatDontLike', 'd')
                ->addSelect('c','l','d')
                ->getQuery();
        }
        else
        {
            $dbquery =  $querybuilder
            ->addSelect('COUNT(l) AS HIDDEN likes') // bez hidden zwróci array: count + entity
            ->leftJoin('p.usersThatLike', 'l')
            ->groupBy('p')
            ->orderBy('likes', 'DESC')
             ->getQuery();
        }

        return $this->paginator->paginate($dbquery, $page, 3);
    }

    public function postDetails($id)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('c.user', 'u')
            ->addSelect('c', 'u') //Eager loading to prevent lazy loading and reduce db queries
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function prepareQuery(string $query): array
    {
        return explode(' ',$query);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
