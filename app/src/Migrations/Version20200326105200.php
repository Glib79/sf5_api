<?php
declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Service\UserManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create database
 */
final class Version20200326105200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create database';
    }

    public function up(Schema $schema): void
    {
        /* Category */
        $this->addSql('CREATE TABLE `category`
        ( 
            `id` CHAR(36) NOT NULL , 
            `name` VARCHAR(100) NOT NULL , 
            `created_at` DATETIME NOT NULL , 
            `modified_at` DATETIME NOT NULL , 
            PRIMARY KEY (`id`)
        ) 
        ENGINE = InnoDB 
        DEFAULT CHARSET=utf8mb4 
        COLLATE utf8mb4_unicode_ci;');
        
        /* User */
        $this->addSql('CREATE TABLE `user` 
        (
            `id` char(36) NOT NULL,
            `email` varchar(45) NOT NULL,
            `password` varchar(255) NOT NULL,
            `roles` json DEFAULT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_user_email` (`email`)
        ) 
        ENGINE=InnoDB 
        DEFAULT CHARSET=utf8mb4 
        COLLATE=utf8mb4_unicode_ci;');
        
//         CREATE TABLE `migration_versions` (
//             `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
//             `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
//             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP category;');
        $this->addSql('DROP user;');
    }
}