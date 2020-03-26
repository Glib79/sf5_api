<?php
declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Service\UserManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Clean database and fill it with default data
 */
final class Version20200326105200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Clean database and fill it with default data';
    }

    public function up(Schema $schema): void
    {
        /* Category */
        /* Clean database */
        $this->addSql('TRUNCATE category;');
        
        /* Insert default data */
        $this->addSql('
            INSERT INTO category (name, created_at, modified_at) VALUES
            ("Category 1", now(), now()),
            ("Category 2", now(), now()),
            ("Category 3", now(), now())
        ');
    }

    public function down(Schema $schema): void
    {
        /* Clean database */
        $this->addSql('TRUNCATE category;');
    }
}