<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823010123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE office ADD user_professional_id INT NOT NULL, CHANGE city_id city_id INT DEFAULT NULL, CHANGE country_id country_id INT DEFAULT NULL, CHANGE state_id state_id INT DEFAULT NULL, CHANGE detail detail LONGTEXT DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL, CHANGE longitude longitude NUMERIC(10, 7) DEFAULT NULL, CHANGE latitude latitude NUMERIC(10, 7) DEFAULT NULL, CHANGE currency currency VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE office ADD CONSTRAINT FK_74516B021F6B1C8C FOREIGN KEY (user_professional_id) REFERENCES user_professional (id)');
        $this->addSql('CREATE INDEX IDX_74516B021F6B1C8C ON office (user_professional_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE office DROP FOREIGN KEY FK_74516B021F6B1C8C');
        $this->addSql('DROP INDEX IDX_74516B021F6B1C8C ON office');
        $this->addSql('ALTER TABLE office DROP user_professional_id, CHANGE city_id city_id INT NOT NULL, CHANGE country_id country_id INT NOT NULL, CHANGE state_id state_id INT NOT NULL, CHANGE detail detail LONGTEXT NOT NULL, CHANGE postal_code postal_code VARCHAR(10) NOT NULL, CHANGE longitude longitude NUMERIC(10, 7) NOT NULL, CHANGE latitude latitude NUMERIC(10, 7) NOT NULL, CHANGE currency currency VARCHAR(3) NOT NULL');
    }
}
