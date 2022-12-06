<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203232930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence_adress (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, number INT NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_AB20229AD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_offre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agenceadresse_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, nb_employees INT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, metier LONGTEXT DEFAULT NULL, services LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FBF094FA76ED395 (user_id), INDEX IDX_4FBF094F8EE41AB8 (agenceadresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE complementaire_infos (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, rib VARCHAR(255) NOT NULL, cni VARCHAR(255) DEFAULT NULL, iban VARCHAR(255) DEFAULT NULL, bic VARCHAR(255) DEFAULT NULL, banque_name VARCHAR(255) DEFAULT NULL, fraisactivation_carte TINYINT(1) DEFAULT NULL, conditiongenerale_vente TINYINT(1) DEFAULT NULL, test_eligibilite TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_B3282C53979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, commercial VARCHAR(255) DEFAULT NULL, contract_state TINYINT(1) DEFAULT NULL, authorized_person1 VARCHAR(255) DEFAULT NULL, authorized_person2 VARCHAR(255) DEFAULT NULL, authorized_person3 VARCHAR(255) DEFAULT NULL, signature LONGTEXT DEFAULT NULL, rib VARCHAR(255) NOT NULL, cni VARCHAR(255) DEFAULT NULL, iban VARCHAR(255) DEFAULT NULL, bic VARCHAR(255) DEFAULT NULL, banque_name VARCHAR(255) DEFAULT NULL, fraisactivation_carte TINYINT(1) DEFAULT NULL, conditiongenerale_vente TINYINT(1) DEFAULT NULL, test_eligibilite TINYINT(1) DEFAULT NULL, contract_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_E98F2859979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hidenprofil TINYINT(1) DEFAULT NULL, downloaddata TINYINT(1) DEFAULT NULL, deletedata TINYINT(1) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, delete_compte TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_2694D7A5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, file_url VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_D8698A76979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, categorieoffre_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, visibility TINYINT(1) NOT NULL, deleted TINYINT(1) DEFAULT NULL, partenaire_info1 VARCHAR(255) DEFAULT NULL, partenaire_info2 VARCHAR(255) DEFAULT NULL, partenaire_info_visibility TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_C6AC3544979B1AD6 (company_id), INDEX IDX_C6AC3544A76ED395 (user_id), INDEX IDX_C6AC354467031F85 (categorieoffre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, agenceadresse_id INT NOT NULL, adress_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday_date DATETIME DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, job VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_verified TINYINT(1) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, hiden_profil TINYINT(1) DEFAULT NULL, download_data TINYINT(1) DEFAULT NULL, delete_data TINYINT(1) DEFAULT NULL, delete_compte TINYINT(1) DEFAULT NULL, rib VARCHAR(255) DEFAULT NULL, cni VARCHAR(255) DEFAULT NULL, numero_compte VARCHAR(255) DEFAULT NULL, validate_num_compte TINYINT(1) DEFAULT NULL, partenaire TINYINT(1) NOT NULL, subscription_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6498486F9AC (adress_id), INDEX IDX_8D93D6498EE41AB8 (agenceadresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agence_adress ADD CONSTRAINT FK_AB20229AD725330D FOREIGN KEY (agence_id) REFERENCES agency (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F8EE41AB8 FOREIGN KEY (agenceadresse_id) REFERENCES agence_adress (id)');
        $this->addSql('ALTER TABLE complementaire_infos ADD CONSTRAINT FK_B3282C53979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC354467031F85 FOREIGN KEY (categorieoffre_id) REFERENCES categorie_offre (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498EE41AB8 FOREIGN KEY (agenceadresse_id) REFERENCES agence_adress (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence_adress DROP FOREIGN KEY FK_AB20229AD725330D');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F8EE41AB8');
        $this->addSql('ALTER TABLE complementaire_infos DROP FOREIGN KEY FK_B3282C53979B1AD6');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859979B1AD6');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5A76ED395');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76979B1AD6');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544979B1AD6');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544A76ED395');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC354467031F85');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498486F9AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498EE41AB8');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE agence_adress');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE categorie_offre');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE complementaire_infos');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
    }
}
