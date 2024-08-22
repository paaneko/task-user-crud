<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240822200933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
        user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        login CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        phone CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        hashed_password VARCHAR(255) NOT NULL,
        role CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
        PRIMARY KEY(user_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE UNIQUE INDEX idx_unique_login_hashed_password ON user (login, hashed_password)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
