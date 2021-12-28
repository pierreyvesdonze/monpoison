<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228125849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE drink (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_DBE40D1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE drink ADD CONSTRAINT FK_DBE40D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE alcool ADD drink_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE alcool ADD CONSTRAINT FK_E2D169FB36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id)');
        $this->addSql('CREATE INDEX IDX_E2D169FB36AA4BB4 ON alcool (drink_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alcool DROP FOREIGN KEY FK_E2D169FB36AA4BB4');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP INDEX IDX_E2D169FB36AA4BB4 ON alcool');
        $this->addSql('ALTER TABLE alcool DROP drink_id');
    }
}
