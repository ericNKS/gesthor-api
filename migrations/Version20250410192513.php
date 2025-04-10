<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410192513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE company (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, cnpj VARCHAR(14) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_4FBF094FC8C6906B ON company (cnpj)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE official (id SERIAL NOT NULL, com_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cpf VARCHAR(11) NOT NULL, date_hiring DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9877320D24F3E595 ON official (com_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE position (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE position_official (position_id INT NOT NULL, official_id INT NOT NULL, PRIMARY KEY(position_id, official_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC5060BADD842E46 ON position_official (position_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC5060BA4D88E615 ON position_official (official_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rule (id SERIAL NOT NULL, com_id_id INT DEFAULT NULL, rule VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_46D8ACCC24F3E595 ON rule (com_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE salary (id SERIAL NOT NULL, off_id_id INT DEFAULT NULL, value NUMERIC(11, 4) NOT NULL, is_active BOOLEAN NOT NULL, received_date DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9413BB71DF8F5950 ON salary (off_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, com_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D64924F3E595 ON "user" (com_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_rule (user_id INT NOT NULL, rule_id INT NOT NULL, PRIMARY KEY(user_id, rule_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C59E005A76ED395 ON user_rule (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C59E005744E0351 ON user_rule (rule_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE official ADD CONSTRAINT FK_9877320D24F3E595 FOREIGN KEY (com_id_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE position_official ADD CONSTRAINT FK_BC5060BADD842E46 FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE position_official ADD CONSTRAINT FK_BC5060BA4D88E615 FOREIGN KEY (official_id) REFERENCES official (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC24F3E595 FOREIGN KEY (com_id_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salary ADD CONSTRAINT FK_9413BB71DF8F5950 FOREIGN KEY (off_id_id) REFERENCES official (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64924F3E595 FOREIGN KEY (com_id_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rule ADD CONSTRAINT FK_3C59E005A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rule ADD CONSTRAINT FK_3C59E005744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE official DROP CONSTRAINT FK_9877320D24F3E595
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE position_official DROP CONSTRAINT FK_BC5060BADD842E46
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE position_official DROP CONSTRAINT FK_BC5060BA4D88E615
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rule DROP CONSTRAINT FK_46D8ACCC24F3E595
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salary DROP CONSTRAINT FK_9413BB71DF8F5950
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64924F3E595
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rule DROP CONSTRAINT FK_3C59E005A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_rule DROP CONSTRAINT FK_3C59E005744E0351
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE official
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE position
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE position_official
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE salary
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_rule
        SQL);
    }
}
