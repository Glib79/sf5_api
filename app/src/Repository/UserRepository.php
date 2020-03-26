<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * UserRepository constructor
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        
        $this->entityManager = $this->getEntityManager();
    }
    
    /**
     * Add new User to database
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $this->entityManager->persist($user);
        
        return $this->save();
    }
    
    /**
     * Delete User from database
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        $this->entityManager->remove($user);
        
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

