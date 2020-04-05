<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\BaseDto;
use App\DTO\UserDto;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Driver\Connection;
use Ramsey\Uuid\Uuid;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @var Connection
     */
    private $connection;
    
    /**
     * UserRepository constructor
     * ManagerRegistry is needed because we have authentication base on UserInterface which needs User Entity and Repository 
     * @param Connection $connection
     * @param ManagerRegistry $registry
     */
    public function __construct(Connection $connection, ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        
        $this->connection = $connection;
    }
    
    /**
     * Add new user to database
     * @param UserDto $user
     * @return bool
     */
    public function createUser(UserDto $user): bool
    {
        $sql = 'INSERT INTO user (id, email, password, roles, created_at, modified_at) 
                VALUES (:id, :email, :password, :roles, :createdAt, :modifiedAt);';
        
        $stmt = $this->connection->prepare($sql);
        
        $now = new DateTime();
        
        $stmt->execute([
            'id'         => Uuid::uuid4()->toString(),
            'email'      => $user->email,
            'password'   => $user->password,
            'roles'      => json_encode($user->roles),
            'createdAt'  => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
            'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB)
        ]);
        
        return true;
    }
}

