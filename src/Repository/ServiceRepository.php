<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findLastThree(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createDate', 'DESC')
            ->setMaxResults(3)               
            ->getQuery()
            ->getResult();
    }

    public function findAllFiltered(?int $categoryId, ?string $query): array
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.category', 'c')             
            ->orderBy('s.createDate', 'ASC');

        if ($categoryId) {
            $qb->andWhere('c.id = :catId')
            ->setParameter('catId', $categoryId);
        }

        if ($query) {
        $qb->andWhere('LOWER(s.name) LIKE LOWER(:search)')
           ->setParameter('search', '%' . $query . '%');
    }

        return $qb->getQuery()->getResult();
    }

    public function findSearchInput(?string $name = null): array
    {
        $queryBuilder = $this->createQueryBuilder('s')                        
            ->orderBy('s.createDate', 'ASC');

        if($name) {
            $arrSearchSegments = explode(' ', $name);
            $queryBuilder
                ->join('s.category', 'c')
                ->join('s.author', 'u');                

            for($i = 0; $i < count($arrSearchSegments); $i++) {

                
                if(trim($arrSearchSegments[$i]) != '') {

                    $queryBuilder
                    ->andWhere("LOWER(s.name) LIKE LOWER(:search_$i) 
                                OR LOWER(s.description) LIKE LOWER(:search_$i) 
                                OR LOWER(c.name) LIKE LOWER(:search_$i)
                                OR LOWER(u.town) LIKE LOWER(:search_$i) 
                                OR LOWER(u.postcode) LIKE LOWER(:search_$i)")
                    ->setParameter("search_$i", '%' . $arrSearchSegments[$i] . '%');

                }
            }
        }
        return $queryBuilder->getQuery()->getResult();
    }
    
}
