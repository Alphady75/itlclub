<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206233434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agency ADD commercial_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E67854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_70C0C6E67854071C ON agency (commercial_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E67854071C');
        $this->addSql('DROP INDEX IDX_70C0C6E67854071C ON agency');
        $this->addSql('ALTER TABLE agency DROP commercial_id');
    }
}
