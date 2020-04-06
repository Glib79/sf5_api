<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\BaseDto;
use App\DTO\UserDto;
use App\Support\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Connection;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * extends ServiceEntityRepository is needed because we have authentication base on UserInterface which needs User Entity and Repository
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    /**
     * @var Connection
     */
    private $connection;
    
    /**
     * UserRepository constructor 
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
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
    
    /**
     * Mathod required by UserLoaderInterface for authentication
     * @param string $username
     * @throws InvalidArgumentException
     * @return User
     */
    public function loadUserByUsername(string $username): User
    {
        $sql = 'SELECT id, email, password, roles FROM user WHERE email = :email;';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'email' => $username,
        ]);
        $data = $stmt->fetch();
        
        if (!$data) {
            throw new InvalidArgumentException('No user!');
        }
        
        $user = new User($data['email'], Uuid::fromString($data['id']));
        $user->setPassword($data['password'])
            ->setRoles(json_decode($data['roles']));
            
        return $user;
    }
}

