<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214025842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE napsis (id INT AUTO_INCREMENT NOT NULL, nacionalidad_id INT DEFAULT NULL, comuna_id INT DEFAULT NULL, padre_nivel_educacional_id INT DEFAULT NULL, madre_nivel_educacional_id INT DEFAULT NULL, apoderado_parentesco_id INT DEFAULT NULL, apoderado_nivel_educacional_id INT DEFAULT NULL, genero_id INT DEFAULT NULL, apoderado_genero_id INT DEFAULT NULL, apoderado_estado_civil_id INT DEFAULT NULL, rut VARCHAR(255) NOT NULL, apellido_paterno VARCHAR(255) DEFAULT NULL, apellido_materno VARCHAR(255) DEFAULT NULL, nombres VARCHAR(255) DEFAULT NULL, fecha_nacimiento DATE DEFAULT NULL, domicilio VARCHAR(255) DEFAULT NULL, numero_telefono_contacto1 INT DEFAULT NULL, padre_nombre VARCHAR(255) DEFAULT NULL, madre_nombre VARCHAR(255) DEFAULT NULL, apoderado_nombre VARCHAR(255) DEFAULT NULL, apoderado_correo_electronico VARCHAR(255) DEFAULT NULL, apoderado_profesion VARCHAR(255) DEFAULT NULL, padres_profesan_religion TINYINT(1) DEFAULT NULL, padres_religion VARCHAR(255) DEFAULT NULL, correo_electronico VARCHAR(255) DEFAULT NULL, apoderado_telefono INT DEFAULT NULL, apoderado_fecha_nacimiento DATETIME DEFAULT NULL, padre_direccion VARCHAR(255) DEFAULT NULL, madre_direccion VARCHAR(255) DEFAULT NULL, apoderado_direccion VARCHAR(255) DEFAULT NULL, padre_rut VARCHAR(255) DEFAULT NULL, madre_rut VARCHAR(255) DEFAULT NULL, apoderado_rut VARCHAR(255) DEFAULT NULL, INDEX IDX_67D51789AB8DC0F8 (nacionalidad_id), INDEX IDX_67D5178973AEFE7 (comuna_id), INDEX IDX_67D51789E38E0B01 (padre_nivel_educacional_id), INDEX IDX_67D51789D17C4D8C (madre_nivel_educacional_id), INDEX IDX_67D51789F878C755 (apoderado_parentesco_id), INDEX IDX_67D517891B423A39 (apoderado_nivel_educacional_id), INDEX IDX_67D51789BCE7B795 (genero_id), INDEX IDX_67D51789A44D26B4 (apoderado_genero_id), INDEX IDX_67D517896542BE09 (apoderado_estado_civil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789AB8DC0F8 FOREIGN KEY (nacionalidad_id) REFERENCES nacionalidad (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D5178973AEFE7 FOREIGN KEY (comuna_id) REFERENCES comuna (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789E38E0B01 FOREIGN KEY (padre_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789D17C4D8C FOREIGN KEY (madre_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789F878C755 FOREIGN KEY (apoderado_parentesco_id) REFERENCES parentesco (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D517891B423A39 FOREIGN KEY (apoderado_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789BCE7B795 FOREIGN KEY (genero_id) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D51789A44D26B4 FOREIGN KEY (apoderado_genero_id) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE napsis ADD CONSTRAINT FK_67D517896542BE09 FOREIGN KEY (apoderado_estado_civil_id) REFERENCES estado_civil (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE napsis');
    }
}
