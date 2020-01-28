<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191211130423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE movies_list_movies (movies_list_id INT NOT NULL, movies_id INT NOT NULL, INDEX IDX_B418EDDF1D51DCC (movies_list_id), INDEX IDX_B418EDD53F590A4 (movies_id), PRIMARY KEY(movies_list_id, movies_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movies_list_movies ADD CONSTRAINT FK_B418EDDF1D51DCC FOREIGN KEY (movies_list_id) REFERENCES movies_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE movies_list_movies ADD CONSTRAINT FK_B418EDD53F590A4 FOREIGN KEY (movies_id) REFERENCES movies (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE movies_list_movies');
    }
}
