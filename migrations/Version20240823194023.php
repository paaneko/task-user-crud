<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240823194023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
        id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        login CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        phone CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        hashed_password VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE UNIQUE INDEX idx_index_login_hashed_password ON user (login, hashed_password)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
