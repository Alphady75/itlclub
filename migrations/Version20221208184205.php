<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208184205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract ADD commercial_id INT DEFAULT NULL, DROP commercial');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F28597854071C FOREIGN KEY (commercial_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E98F28597854071C ON contract (commercial_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F28597854071C');
        $this->addSql('DROP INDEX IDX_E98F28597854071C ON contract');
        $this->addSql('ALTER TABLE contract ADD commercial VARCHAR(255) DEFAULT NULL, DROP commercial_id');
    }
}
