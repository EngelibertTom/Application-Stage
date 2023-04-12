<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518082323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, date_rã©alisation DATETIME NOT NULL, observation LONGTEXT NOT NULL, urgence INT NOT NULL, utilisateur LONGTEXT NOT NULL, realisation TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipement CHANGE qr_code qr_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE greenhouse CHANGE location_id location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lot CHANGE recovery_type_id recovery_type_id INT DEFAULT NULL, CHANGE qr_code qr_code VARCHAR(255) DEFAULT NULL, CHANGE entry_date entry_date DATE DEFAULT NULL, CHANGE place place VARCHAR(255) DEFAULT NULL, CHANGE age_recovery age_recovery INT DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lot_picture CHANGE path_original path_original VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nursery CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE owner CHANGE phone phone VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE last_connection last_connection DATETIME DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE pot_type CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE capacity capacity DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE species CHANGE status_uicn_id status_uicn_id INT DEFAULT NULL, CHANGE leaf_type_id leaf_type_id INT DEFAULT NULL, CHANGE recommended_soil_moisture recommended_soil_moisture INT DEFAULT NULL, CHANGE recommended_acidity_min recommended_acidity_min INT DEFAULT NULL, CHANGE recommended_acidity_max recommended_acidity_max INT DEFAULT NULL, CHANGE fertilizer_need fertilizer_need INT DEFAULT NULL, CHANGE flowering_month flowering_month INT DEFAULT NULL, CHANGE recommended_exposure recommended_exposure INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status_uicn CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE style CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tree CHANGE species_id species_id INT DEFAULT NULL, CHANGE position_id position_id INT DEFAULT NULL, CHANGE output_type_id output_type_id INT DEFAULT NULL, CHANGE lot_id lot_id INT DEFAULT NULL, CHANGE pot_type_id pot_type_id INT DEFAULT NULL, CHANGE greenhouse_id greenhouse_id INT DEFAULT NULL, CHANGE culture_table_id culture_table_id INT DEFAULT NULL, CHANGE segment_id segment_id INT DEFAULT NULL, CHANGE table_column_id table_column_id INT DEFAULT NULL, CHANGE column_row_id column_row_id INT DEFAULT NULL, CHANGE qr_code qr_code VARCHAR(255) DEFAULT NULL, CHANGE age_recovery age_recovery INT DEFAULT NULL, CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE nebari_diameter nebari_diameter DOUBLE PRECISION DEFAULT NULL, CHANGE trunk_diameter trunk_diameter DOUBLE PRECISION DEFAULT NULL, CHANGE working_year working_year INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_growth CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE nebari_diameter nebari_diameter DOUBLE PRECISION DEFAULT NULL, CHANGE trunk_diameter trunk_diameter DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_picture CHANGE path_original path_original VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entretien');
        $this->addSql('ALTER TABLE category CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE equipement CHANGE qr_code qr_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE greenhouse CHANGE location_id location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lot CHANGE recovery_type_id recovery_type_id INT DEFAULT NULL, CHANGE qr_code qr_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE entry_date entry_date DATE DEFAULT \'NULL\', CHANGE place place VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE age_recovery age_recovery INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lot_picture CHANGE path_original path_original VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nursery CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE owner CHANGE phone phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_connection last_connection DATETIME DEFAULT \'NULL\', CHANGE postal_code postal_code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pot_type CHANGE height height DOUBLE PRECISION DEFAULT \'NULL\', CHANGE capacity capacity DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE species CHANGE status_uicn_id status_uicn_id INT DEFAULT NULL, CHANGE leaf_type_id leaf_type_id INT DEFAULT NULL, CHANGE recommended_soil_moisture recommended_soil_moisture INT DEFAULT NULL, CHANGE recommended_acidity_min recommended_acidity_min INT DEFAULT NULL, CHANGE recommended_acidity_max recommended_acidity_max INT DEFAULT NULL, CHANGE fertilizer_need fertilizer_need INT DEFAULT NULL, CHANGE flowering_month flowering_month INT DEFAULT NULL, CHANGE recommended_exposure recommended_exposure INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status_uicn CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE style CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tree CHANGE species_id species_id INT DEFAULT NULL, CHANGE position_id position_id INT DEFAULT NULL, CHANGE output_type_id output_type_id INT DEFAULT NULL, CHANGE lot_id lot_id INT DEFAULT NULL, CHANGE pot_type_id pot_type_id INT DEFAULT NULL, CHANGE greenhouse_id greenhouse_id INT DEFAULT NULL, CHANGE culture_table_id culture_table_id INT DEFAULT NULL, CHANGE segment_id segment_id INT DEFAULT NULL, CHANGE table_column_id table_column_id INT DEFAULT NULL, CHANGE column_row_id column_row_id INT DEFAULT NULL, CHANGE qr_code qr_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE age_recovery age_recovery INT DEFAULT NULL, CHANGE height height DOUBLE PRECISION DEFAULT \'NULL\', CHANGE nebari_diameter nebari_diameter DOUBLE PRECISION DEFAULT \'NULL\', CHANGE trunk_diameter trunk_diameter DOUBLE PRECISION DEFAULT \'NULL\', CHANGE working_year working_year INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tree_growth CHANGE height height DOUBLE PRECISION DEFAULT \'NULL\', CHANGE nebari_diameter nebari_diameter DOUBLE PRECISION DEFAULT \'NULL\', CHANGE trunk_diameter trunk_diameter DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tree_picture CHANGE path_original path_original VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
