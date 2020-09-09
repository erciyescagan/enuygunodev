<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908160320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE company ADD status VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE developers ADD status VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE params ADD company_id INT NOT NULL');
        $this->addSql('ALTER TABLE project ADD status VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE provider ADD status VARCHAR(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company DROP status');
        $this->addSql('ALTER TABLE developers DROP status');
        $this->addSql('ALTER TABLE params DROP company_id');
        $this->addSql('ALTER TABLE project DROP status');
        $this->addSql('ALTER TABLE provider DROP status');
    }
}
