<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228130302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alcool DROP FOREIGN KEY FK_E2D169FB36AA4BB4');
        $this->addSql('DROP INDEX IDX_E2D169FB36AA4BB4 ON alcool');
        $this->addSql('ALTER TABLE alcool DROP drink_id');
        $this->addSql('ALTER TABLE drink ADD alcool_id INT NOT NULL');
        $this->addSql('ALTER TABLE drink ADD CONSTRAINT FK_DBE40D19A3B6B4F FOREIGN KEY (alcool_id) REFERENCES alcool (id)');
        $this->addSql('CREATE INDEX IDX_DBE40D19A3B6B4F ON drink (alcool_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alcool ADD drink_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alcool ADD CONSTRAINT FK_E2D169FB36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E2D169FB36AA4BB4 ON alcool (drink_id)');
        $this->addSql('ALTER TABLE drink DROP FOREIGN KEY FK_DBE40D19A3B6B4F');
        $this->addSql('DROP INDEX IDX_DBE40D19A3B6B4F ON drink');
        $this->addSql('ALTER TABLE drink DROP alcool_id');
    }
}
