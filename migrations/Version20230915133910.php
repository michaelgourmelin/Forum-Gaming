<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230915133910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visits_theme (visits_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_2F90BC49B4B7D41A (visits_id), INDEX IDX_2F90BC4959027487 (theme_id), PRIMARY KEY(visits_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visits_theme ADD CONSTRAINT FK_2F90BC49B4B7D41A FOREIGN KEY (visits_id) REFERENCES visits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visits_theme ADD CONSTRAINT FK_2F90BC4959027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visits_theme DROP FOREIGN KEY FK_2F90BC49B4B7D41A');
        $this->addSql('ALTER TABLE visits_theme DROP FOREIGN KEY FK_2F90BC4959027487');
        $this->addSql('DROP TABLE visits_theme');
    }
}
