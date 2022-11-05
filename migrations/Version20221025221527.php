<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025221527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD id_owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C12EE78D6C FOREIGN KEY (id_owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64C19C12EE78D6C ON category (id_owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C12EE78D6C');
        $this->addSql('DROP INDEX IDX_64C19C12EE78D6C ON category');
        $this->addSql('ALTER TABLE category DROP id_owner_id');
    }
}
