<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140219213457 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE package_money (id INT AUTO_INCREMENT NOT NULL, sponsor_id INT DEFAULT NULL, value INT NOT NULL, startDate DATE NOT NULL, endDate DATE NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_22DB140312F7FB51 (sponsor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE package_meeting (id INT AUTO_INCREMENT NOT NULL, sponsor_id INT DEFAULT NULL, meeting_date DATE NOT NULL, meeting_type VARCHAR(30) NOT NULL, INDEX IDX_7A4E84B512F7FB51 (sponsor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, url VARCHAR(255) NOT NULL, logo_extension VARCHAR(255) NOT NULL, logo_md5 VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE package_money ADD CONSTRAINT FK_22DB140312F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id)");
        $this->addSql("ALTER TABLE package_meeting ADD CONSTRAINT FK_7A4E84B512F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE package_money DROP FOREIGN KEY FK_22DB140312F7FB51");
        $this->addSql("ALTER TABLE package_meeting DROP FOREIGN KEY FK_7A4E84B512F7FB51");
        $this->addSql("DROP TABLE package_money");
        $this->addSql("DROP TABLE package_meeting");
        $this->addSql("DROP TABLE sponsor");
    }
}
