<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227140003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colletion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, artist VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, is_favorite TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3EAF606A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE colletion_products (colletion_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_F620F08A60F5F4DC (colletion_id), INDEX IDX_F620F08A6C8A81A9 (products_id), PRIMARY KEY(colletion_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, subject VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, colletion_id INT NOT NULL, mark INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6674F271A76ED395 (user_id), INDEX IDX_6674F27160F5F4DC (colletion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, size VARCHAR(255) NOT NULL, property VARCHAR(2000) NOT NULL, artist VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B3BA5A5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(50) NOT NULL, pseudo VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colletion ADD CONSTRAINT FK_3EAF606A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE colletion_products ADD CONSTRAINT FK_F620F08A60F5F4DC FOREIGN KEY (colletion_id) REFERENCES colletion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE colletion_products ADD CONSTRAINT FK_F620F08A6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F27160F5F4DC FOREIGN KEY (colletion_id) REFERENCES colletion (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colletion DROP FOREIGN KEY FK_3EAF606A76ED395');
        $this->addSql('ALTER TABLE colletion_products DROP FOREIGN KEY FK_F620F08A60F5F4DC');
        $this->addSql('ALTER TABLE colletion_products DROP FOREIGN KEY FK_F620F08A6C8A81A9');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271A76ED395');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F27160F5F4DC');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AA76ED395');
        $this->addSql('DROP TABLE colletion');
        $this->addSql('DROP TABLE colletion_products');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE mark');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
