<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614083038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE villes_france_free');
        $this->addSql('ALTER TABLE user ADD picture VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE villes_france_free (ville_id INT UNSIGNED AUTO_INCREMENT NOT NULL, ville_departement VARCHAR(3) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_slug VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_nom VARCHAR(45) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_nom_simple VARCHAR(45) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_nom_reel VARCHAR(45) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_nom_soundex VARCHAR(20) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_nom_metaphone VARCHAR(22) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_code_postal VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_commune VARCHAR(3) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_code_commune VARCHAR(5) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, ville_arrondissement SMALLINT UNSIGNED DEFAULT NULL, ville_canton VARCHAR(4) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_amdi SMALLINT UNSIGNED DEFAULT NULL, ville_population_2010 INT UNSIGNED DEFAULT NULL, ville_population_1999 INT UNSIGNED DEFAULT NULL, ville_population_2012 INT UNSIGNED DEFAULT NULL COMMENT \'approximatif\', ville_densite_2010 INT DEFAULT NULL, ville_surface DOUBLE PRECISION DEFAULT NULL, ville_longitude_deg DOUBLE PRECISION DEFAULT NULL, ville_latitude_deg DOUBLE PRECISION DEFAULT NULL, ville_longitude_grd VARCHAR(9) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_latitude_grd VARCHAR(8) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_longitude_dms VARCHAR(9) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_latitude_dms VARCHAR(8) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, ville_zmin INT DEFAULT NULL, ville_zmax INT DEFAULT NULL, INDEX ville_code_commune (ville_code_commune), UNIQUE INDEX ville_code_commune_2 (ville_code_commune), INDEX ville_code_postal (ville_code_postal), INDEX ville_departement (ville_departement), INDEX ville_longitude_latitude_deg (ville_longitude_deg, ville_latitude_deg), INDEX ville_nom (ville_nom), INDEX ville_nom_metaphone (ville_nom_metaphone), INDEX ville_nom_reel (ville_nom_reel), INDEX ville_nom_simple (ville_nom_simple), INDEX ville_nom_soundex (ville_nom_soundex), INDEX ville_population_2010 (ville_population_2010), UNIQUE INDEX ville_slug (ville_slug), PRIMARY KEY(ville_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `user` DROP picture');
    }
}
