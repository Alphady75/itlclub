<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206231224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solipac CHANGE nb_jours nb_jours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE demande_methode demande_methode LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE formation_com formation_com LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE formation_qua formation_qua LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE accomp_tech accomp_tech LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE accomp_admin accomp_admin LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE accomp_assurance accomp_assurance LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE besoin_apprenti besoin_apprenti LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solipac CHANGE nb_jours nb_jours VARCHAR(255) DEFAULT NULL, CHANGE demande_methode demande_methode VARCHAR(255) DEFAULT NULL, CHANGE formation_com formation_com VARCHAR(255) DEFAULT NULL, CHANGE formation_qua formation_qua VARCHAR(255) DEFAULT NULL, CHANGE accomp_tech accomp_tech VARCHAR(255) DEFAULT NULL, CHANGE accomp_admin accomp_admin VARCHAR(255) DEFAULT NULL, CHANGE accomp_assurance accomp_assurance VARCHAR(255) DEFAULT NULL, CHANGE besoin_apprenti besoin_apprenti VARCHAR(255) DEFAULT NULL');
    }
}
