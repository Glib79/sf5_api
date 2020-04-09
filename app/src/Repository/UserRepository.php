<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\BaseDto;
use App\DTO\UserDto;
use App\Support\User;
use DateTime;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class UserRepository extends BaseRepository
{
    /**
     * Add new user to database
     * @param UserDto $user
     * @return string $id - created record id
     */
    public function createUser(UserDto $user): string
    {
        $sql = 'INSERT INTO user (id, email, password, roles, created_at, modified_at) 
                VALUES (:id, :email, :password, :roles, :createdAt, :modifiedAt);';
        
        $now = new DateTime();
        $id = Uuid::uuid4()->toString();
        
        $stmt = $this->execute(
            $this->readConn, 
            $sql, 
            [
                'id'         => $id,
                'email'      => $user->email,
                'password'   => $user->password,
                'roles'      => json_encode($user->roles),
                'createdAt'  => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
                'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB)
            ]
        );
        
        return $id;
    }
    
    /**
     * Get single user by id
     * @param string $id
     * @return array
     */
    public function getUserById(string $id): array
    {
        $sql = 'SELECT * FROM user WHERE id = :id;';

        $stmt = $this->execute($this->readConn, $sql, ['id' => $id]);
        
        return $stmt->fetch() ?: [];
    }
}

