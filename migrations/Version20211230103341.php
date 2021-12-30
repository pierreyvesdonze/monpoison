<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211230103341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink ADD user_id INT NOT NULL, ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE drink ADD CONSTRAINT FK_DBE40D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DBE40D1A76ED395 ON drink (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink DROP FOREIGN KEY FK_DBE40D1A76ED395');
        $this->addSql('DROP INDEX IDX_DBE40D1A76ED395 ON drink');
        $this->addSql('ALTER TABLE drink DROP user_id, DROP date');
    }
}
