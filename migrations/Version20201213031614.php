<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201213031614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estado_civil (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genero (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matricula ADD genero_id INT DEFAULT NULL, ADD apoderado_genero_id INT DEFAULT NULL, ADD apoderado_estado_civil_id INT DEFAULT NULL, ADD telefono INT DEFAULT NULL, ADD correo_electronico VARCHAR(255) DEFAULT NULL, ADD apoderado_telefono INT DEFAULT NULL, ADD apoderado_fecha_nacimiento DATETIME DEFAULT NULL, ADD padre_direccion VARCHAR(255) DEFAULT NULL, ADD madre_direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885BCE7B795 FOREIGN KEY (genero_id) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885A44D26B4 FOREIGN KEY (apoderado_genero_id) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF18856542BE09 FOREIGN KEY (apoderado_estado_civil_id) REFERENCES estado_civil (id)');
        $this->addSql('CREATE INDEX IDX_15DF1885BCE7B795 ON matricula (genero_id)');
        $this->addSql('CREATE INDEX IDX_15DF1885A44D26B4 ON matricula (apoderado_genero_id)');
        $this->addSql('CREATE INDEX IDX_15DF18856542BE09 ON matricula (apoderado_estado_civil_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF18856542BE09');
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885BCE7B795');
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885A44D26B4');
        $this->addSql('DROP TABLE estado_civil');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP INDEX IDX_15DF1885BCE7B795 ON matricula');
        $this->addSql('DROP INDEX IDX_15DF1885A44D26B4 ON matricula');
        $this->addSql('DROP INDEX IDX_15DF18856542BE09 ON matricula');
        $this->addSql('ALTER TABLE matricula DROP genero_id, DROP apoderado_genero_id, DROP apoderado_estado_civil_id, DROP telefono, DROP correo_electronico, DROP apoderado_telefono, DROP apoderado_fecha_nacimiento, DROP padre_direccion, DROP madre_direccion');
    }
}
