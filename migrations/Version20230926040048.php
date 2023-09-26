<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926040048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_20201547187B2D12');
        $this->addSql('DROP INDEX IDX_20201547187B2D12 ON turn');
        $this->addSql('ALTER TABLE turn ADD cancelled_by INT NOT NULL, DROP cancelled_by_id');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_202015471F6B1C8C FOREIGN KEY (user_professional_id) REFERENCES user_professional (id)');
        $this->addSql('CREATE INDEX IDX_202015471F6B1C8C ON turn (user_professional_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_202015471F6B1C8C');
        $this->addSql('DROP INDEX IDX_202015471F6B1C8C ON turn');
        $this->addSql('ALTER TABLE turn ADD cancelled_by_id INT DEFAULT NULL, DROP cancelled_by');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_20201547187B2D12 FOREIGN KEY (cancelled_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_20201547187B2D12 ON turn (cancelled_by_id)');
    }
}
