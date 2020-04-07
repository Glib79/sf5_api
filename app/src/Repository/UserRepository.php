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
     * @return bool
     */
    public function createUser(UserDto $user): bool
    {
        $sql = 'INSERT INTO user (id, email, password, roles, created_at, modified_at) 
                VALUES (:id, :email, :password, :roles, :createdAt, :modifiedAt);';
        
        $stmt = $this->writeConn->prepare($sql);
        
        $now = new DateTime();
        
        $stmt->execute([
            'id'         => Uuid::uuid4()->toString(),
            'email'      => $user->email,
            'password'   => $user->password,
            'roles'      => json_encode($user->roles),
            'createdAt'  => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
            'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB)
        ]);
        
        return $stmt->errorCode() === '00000';
    }
}

