<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507192302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, source_id INT NOT NULL, recipient_id INT NOT NULL, ammount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1953C1C61 ON transaction (source_id)');
        $this->addSql('CREATE INDEX IDX_723705D1E92F8F78 ON transaction (recipient_id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1953C1C61 FOREIGN KEY (source_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1E92F8F78 FOREIGN KEY (recipient_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1953C1C61');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1E92F8F78');
        $this->addSql('DROP TABLE transaction');
    }
}
