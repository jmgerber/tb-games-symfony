<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422164652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE riddle (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, pictures VARCHAR(255) DEFAULT NULL, INDEX IDX_6C00AA81E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE riddle ADD CONSTRAINT FK_6C00AA81E48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE games DROP riddles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE riddle DROP FOREIGN KEY FK_6C00AA81E48FD905');
        $this->addSql('DROP TABLE riddle');
        $this->addSql('ALTER TABLE games ADD riddles JSON NOT NULL');
    }
}
