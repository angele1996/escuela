<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214143635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matricula ADD parentesco_telefono_contacto1_id INT DEFAULT NULL, ADD parentesco_telefono_contacto2_id INT DEFAULT NULL, ADD parentesco_telefono_contacto3_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF188578A2A197 FOREIGN KEY (parentesco_telefono_contacto1_id) REFERENCES parentesco (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF18856A170E79 FOREIGN KEY (parentesco_telefono_contacto2_id) REFERENCES parentesco (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885D2AB691C FOREIGN KEY (parentesco_telefono_contacto3_id) REFERENCES parentesco (id)');
        $this->addSql('CREATE INDEX IDX_15DF188578A2A197 ON matricula (parentesco_telefono_contacto1_id)');
        $this->addSql('CREATE INDEX IDX_15DF18856A170E79 ON matricula (parentesco_telefono_contacto2_id)');
        $this->addSql('CREATE INDEX IDX_15DF1885D2AB691C ON matricula (parentesco_telefono_contacto3_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF188578A2A197');
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF18856A170E79');
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885D2AB691C');
        $this->addSql('DROP INDEX IDX_15DF188578A2A197 ON matricula');
        $this->addSql('DROP INDEX IDX_15DF18856A170E79 ON matricula');
        $this->addSql('DROP INDEX IDX_15DF1885D2AB691C ON matricula');
        $this->addSql('ALTER TABLE matricula DROP parentesco_telefono_contacto1_id, DROP parentesco_telefono_contacto2_id, DROP parentesco_telefono_contacto3_id');
    }
}
