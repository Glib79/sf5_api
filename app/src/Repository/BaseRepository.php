<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository
{
    /**
     * @var Connection
     */
    protected $readConn;
    
    /**
     * @var Connection
     */
    protected $writeConn;
    
    /**
     * BaseRepository constructor
     * @param Connection $connection
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->readConn = $doctrine->getConnection('default');
        $this->writeConn = $doctrine->getConnection('write');
    }
}