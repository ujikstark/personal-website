<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301095120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE created_at created_at TIMESTAMP(0) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE updated_at updated_at TIMESTAMP(0) DEFAULT NULL');
        $this->addSql('ALTER TABLE todo CHANGE date date TIMESTAMP(0) DEFAULT NULL');
        $this->addSql('ALTER TABLE todo CHANGE reminder reminder TIMESTAMP(0) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
