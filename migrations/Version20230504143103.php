<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504143103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE secret (id INT AUTO_INCREMENT NOT NULL, user_uuid VARCHAR(180) NOT NULL, category_id INT NOT NULL, to_delete TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_5CA2E8E5ABFE1C6F (user_uuid), INDEX IDX_5CA2E8E512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secret_category (id INT AUTO_INCREMENT NOT NULL, user_uuid VARCHAR(180) NOT NULL, name LONGTEXT NOT NULL, INDEX IDX_A360AC1AABFE1C6F (user_uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E5ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES user (uuid)');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E512469DE2 FOREIGN KEY (category_id) REFERENCES secret_category (id)');
        $this->addSql('ALTER TABLE secret_category ADD CONSTRAINT FK_A360AC1AABFE1C6F FOREIGN KEY (user_uuid) REFERENCES user (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secret DROP FOREIGN KEY FK_5CA2E8E5ABFE1C6F');
        $this->addSql('ALTER TABLE secret DROP FOREIGN KEY FK_5CA2E8E512469DE2');
        $this->addSql('ALTER TABLE secret_category DROP FOREIGN KEY FK_A360AC1AABFE1C6F');
        $this->addSql('DROP TABLE secret');
        $this->addSql('DROP TABLE secret_category');
    }
}
