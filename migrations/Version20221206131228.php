<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206131228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE solipac (id INT AUTO_INCREMENT NOT NULL, agency_id INT NOT NULL, conseiller VARCHAR(255) DEFAULT NULL, societe VARCHAR(255) DEFAULT NULL, societe_tel VARCHAR(255) DEFAULT NULL, societe_email VARCHAR(255) DEFAULT NULL, societe_adresse VARCHAR(255) DEFAULT NULL, societe_siret VARCHAR(255) DEFAULT NULL, societe_date_creation DATETIME DEFAULT NULL, societe_effectif INT DEFAULT NULL, societe_qualification VARCHAR(255) DEFAULT NULL, gerant VARCHAR(255) DEFAULT NULL, gerant_tel VARCHAR(255) DEFAULT NULL, gerant_mail VARCHAR(255) DEFAULT NULL, ape VARCHAR(255) DEFAULT NULL, ca2021 INT DEFAULT NULL, composition VARCHAR(255) DEFAULT NULL, zone_geo_inter VARCHAR(255) DEFAULT NULL, pac_air_eau TINYINT(1) DEFAULT NULL, pac_air_eau_marque VARCHAR(255) DEFAULT NULL, pac_air_eau_volume INT DEFAULT NULL, pac_air_air TINYINT(1) DEFAULT NULL, pac_air_air_marque VARCHAR(255) DEFAULT NULL, pac_air_air_volume INT DEFAULT NULL, ballon_thermo TINYINT(1) NOT NULL, ballon_thermo_marque VARCHAR(255) DEFAULT NULL, ballon_thermo_volume INT DEFAULT NULL, biomasse TINYINT(1) DEFAULT NULL, biomasse_marque VARCHAR(255) DEFAULT NULL, biomasse_volume INT DEFAULT NULL, plan_chauffant TINYINT(1) DEFAULT NULL, plan_chauffant_marque VARCHAR(255) DEFAULT NULL, plan_chauffant_volume INT DEFAULT NULL, adoucisseur TINYINT(1) DEFAULT NULL, adoucisseur_marque VARCHAR(255) DEFAULT NULL, adoucisseur_volume INT DEFAULT NULL, ventilation TINYINT(1) DEFAULT NULL, ventilation_marque VARCHAR(255) DEFAULT NULL, ventilation_volume INT DEFAULT NULL, renovation TINYINT(1) DEFAULT NULL, renov_pourcentage DOUBLE PRECISION DEFAULT NULL, neuf TINYINT(1) DEFAULT NULL, neuf_pourcentage DOUBLE PRECISION DEFAULT NULL, volume_achat DOUBLE PRECISION DEFAULT NULL, nb_jours VARCHAR(255) DEFAULT NULL, encours_demande DOUBLE PRECISION DEFAULT NULL, demande_methode VARCHAR(255) DEFAULT NULL, formation_com VARCHAR(255) DEFAULT NULL, formation_qua VARCHAR(255) DEFAULT NULL, accomp_tech VARCHAR(255) DEFAULT NULL, accomp_admin VARCHAR(255) DEFAULT NULL, accomp_assurance VARCHAR(255) DEFAULT NULL, besoin_apprenti VARCHAR(255) DEFAULT NULL, obj_client LONGTEXT DEFAULT NULL, appreciation_gen LONGTEXT DEFAULT NULL, date_rdv1 DATETIME DEFAULT NULL, date_rdv2 DATETIME DEFAULT NULL, origine_contact VARCHAR(255) DEFAULT NULL, autre_commentatire LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_5F0E88D6CDEADB2A (agency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE solipac ADD CONSTRAINT FK_5F0E88D6CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solipac DROP FOREIGN KEY FK_5F0E88D6CDEADB2A');
        $this->addSql('DROP TABLE solipac');
    }
}
