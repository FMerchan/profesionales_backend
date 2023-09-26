<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926024723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_202015471F6B1C8C');
        $this->addSql('DROP INDEX IDX_202015471F6B1C8C ON turn');
        $this->addSql('ALTER TABLE turn ADD cancelled_by_id INT DEFAULT NULL, ADD cancelled TINYINT(1) NOT NULL, CHANGE user_professional_id user_professional INT NOT NULL');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_20201547187B2D12 FOREIGN KEY (cancelled_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_20201547187B2D12 ON turn (cancelled_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_20201547187B2D12');
        $this->addSql('DROP INDEX IDX_20201547187B2D12 ON turn');
        $this->addSql('ALTER TABLE turn DROP cancelled_by_id, DROP cancelled, CHANGE user_professional user_professional_id INT NOT NULL');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_202015471F6B1C8C FOREIGN KEY (user_professional_id) REFERENCES user_professional (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_202015471F6B1C8C ON turn (user_professional_id)');
    }
}