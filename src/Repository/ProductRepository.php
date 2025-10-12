<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAvailable(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.disponible = :disponible')
            ->andWhere('p.stock > 0')
            ->setParameter('disponible', true)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :category')
            ->andWhere('p.disponible = :disponible')
            ->setParameter('category', $category)
            ->setParameter('disponible', true)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchProducts(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :query OR p.description LIKE :query')
            ->andWhere('p.disponible = :disponible')
            ->setParameter('query', '%'.$query.'%')
            ->setParameter('disponible', true)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
