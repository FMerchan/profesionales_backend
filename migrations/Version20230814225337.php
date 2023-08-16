<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814225337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE office (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, country_id INT NOT NULL, state_id INT NOT NULL, name VARCHAR(255) NOT NULL, detail LONGTEXT NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, longitude NUMERIC(10, 7) NOT NULL, latitude NUMERIC(10, 7) NOT NULL, price NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, business_days JSON DEFAULT NULL, duration INT NOT NULL, available_times JSON DEFAULT NULL, INDEX IDX_74516B028BAC62AF (city_id), INDEX IDX_74516B02F92F3E70 (country_id), INDEX IDX_74516B025D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turn (id INT AUTO_INCREMENT NOT NULL, user_professional_id INT NOT NULL, office_id INT NOT NULL, date DATETIME NOT NULL, duration INT NOT NULL, INDEX IDX_202015471F6B1C8C (user_professional_id), INDEX IDX_20201547FFA0C224 (office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_professional (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, phone_number VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, license_number VARCHAR(255) NOT NULL, authenticator_data JSON DEFAULT NULL, INDEX IDX_33790FA8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_professional_professional (id INT AUTO_INCREMENT NOT NULL, user_professional_id INT NOT NULL, professional_id INT NOT NULL, INDEX IDX_D4C666B01F6B1C8C (user_professional_id), INDEX IDX_D4C666B0DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B028BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B02F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B025D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_202015471F6B1C8C FOREIGN KEY (user_professional_id) REFERENCES user_professional (id)');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_20201547FFA0C224 FOREIGN KEY (office_id) REFERENCES office (id)');
        $this->addSql('ALTER TABLE user_professional ADD CONSTRAINT FK_33790FA8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_professional_professional ADD CONSTRAINT FK_D4C666B01F6B1C8C FOREIGN KEY (user_professional_id) REFERENCES user_professional (id)');
        $this->addSql('ALTER TABLE user_professional_professional ADD CONSTRAINT FK_D4C666B0DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B028BAC62AF');
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B02F92F3E70');
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B025D83CC1');
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_202015471F6B1C8C');
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_20201547FFA0C224');
        $this->addSql('ALTER TABLE user_professional DROP FOREIGN KEY FK_33790FA8A76ED395');
        $this->addSql('ALTER TABLE user_professional_professional DROP FOREIGN KEY FK_D4C666B01F6B1C8C');
        $this->addSql('ALTER TABLE user_professional_professional DROP FOREIGN KEY FK_D4C666B0DB77003');
        $this->addSql('DROP TABLE office');
        $this->addSql('DROP TABLE turn');
        $this->addSql('DROP TABLE user_professional');
        $this->addSql('DROP TABLE user_professional_professional');
    }
}
