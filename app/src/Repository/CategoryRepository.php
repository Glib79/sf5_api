<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * CategoryRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
        
        $this->entityManager = $this->getEntityManager();
    }
    
    /**
     * Add new Category to database
     * @param Category $category
     * @return bool
     */
    public function create(Category $category): bool
    {
        $this->entityManager->persist($category);
        
        return $this->save();
    }
    
    /**
     * Delete Category from database
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category): bool
    {
        $this->entityManager->remove($category);
        
        return $this->save();
    }
    
    /**
     * Save changes to database
     * @return bool
     */
    public function save(): bool
    {
        $this->entityManager->flush();
        
        return true;
    }
}

