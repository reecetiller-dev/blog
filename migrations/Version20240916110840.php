<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916110840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, short_bio VARCHAR(500) NOT NULL, phone VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BDAFD8C8D1B862B8 (hash), UNIQUE INDEX UNIQ_BDAFD8C85E237E06 (name), UNIQUE INDEX UNIQ_BDAFD8C8F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, blog_post_history_id INT DEFAULT NULL, hash VARCHAR(10) NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BA5AE01DD1B862B8 (hash), INDEX IDX_BA5AE01DF675F31B (author_id), INDEX IDX_BA5AE01D8F84F110 (blog_post_history_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_post_history (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT DEFAULT NULL, hash VARCHAR(10) NOT NULL, action VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, body LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_408E28FFD1B862B8 (hash), INDEX IDX_408E28FFF675F31B (author_id), INDEX IDX_408E28FF4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01D8F84F110 FOREIGN KEY (blog_post_history_id) REFERENCES blog_post_history (id)');
        $this->addSql('ALTER TABLE blog_post_history ADD CONSTRAINT FK_408E28FFF675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE blog_post_history ADD CONSTRAINT FK_408E28FF4B89032C FOREIGN KEY (post_id) REFERENCES blog_post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01DF675F31B');
        $this->addSql('ALTER TABLE blog_post DROP FOREIGN KEY FK_BA5AE01D8F84F110');
        $this->addSql('ALTER TABLE blog_post_history DROP FOREIGN KEY FK_408E28FFF675F31B');
        $this->addSql('ALTER TABLE blog_post_history DROP FOREIGN KEY FK_408E28FF4B89032C');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('DROP TABLE blog_post_history');
    }
}
