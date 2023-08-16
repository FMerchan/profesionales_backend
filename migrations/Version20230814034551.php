<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814034551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE professional (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, has_specification TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_specification (id INT AUTO_INCREMENT NOT NULL, professional_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_10553129DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE professional_specification ADD CONSTRAINT FK_10553129DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649B63E2EC7 ON user');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professional_specification DROP FOREIGN KEY FK_10553129DB77003');
        $this->addSql('DROP TABLE professional');
        $this->addSql('DROP TABLE professional_specification');
        $this->addSql('ALTER TABLE user CHANGE roles roles VARCHAR(250) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B63E2EC7 ON user (roles)');
    }
}
