<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212155932 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matricula (id INT AUTO_INCREMENT NOT NULL, curso_id INT NOT NULL, nacionalidad_id INT DEFAULT NULL, comuna_id INT DEFAULT NULL, con_quien_vive_id INT DEFAULT NULL, padre_nivel_educacional_id INT DEFAULT NULL, madre_nivel_educacional_id INT DEFAULT NULL, apoderado_parentesco_id INT DEFAULT NULL, apoderado_nivel_educacional_id INT DEFAULT NULL, quien_retira_parentesco_id INT DEFAULT NULL, fecha_registro DATETIME NOT NULL, rut VARCHAR(255) NOT NULL, apellido_paterno VARCHAR(255) NOT NULL, apellido_materno VARCHAR(255) DEFAULT NULL, nombres VARCHAR(255) DEFAULT NULL, fecha_nacimiento DATE DEFAULT NULL, ciudad_nacimiento VARCHAR(255) DEFAULT NULL, domicilio VARCHAR(255) DEFAULT NULL, nombre_telefono_contacto1 VARCHAR(255) DEFAULT NULL, numero_telefono_contacto1 INT DEFAULT NULL, nombre_telefono_contacto2 VARCHAR(255) DEFAULT NULL, numero_telefono_contacto2 INT DEFAULT NULL, nombre_telefono_contacto3 VARCHAR(255) DEFAULT NULL, numero_telefono_contacto3 INT DEFAULT NULL, colegio_procedencia VARCHAR(255) DEFAULT NULL, repite_curso TINYINT(1) DEFAULT NULL, necesidades_educativas_especiales TINYINT(1) DEFAULT NULL, padre_nombre VARCHAR(255) DEFAULT NULL, padre_correo_electronico VARCHAR(255) DEFAULT NULL, padre_profesion VARCHAR(255) DEFAULT NULL, padre_lugar_trabajo VARCHAR(255) DEFAULT NULL, padre_direccion_trabajo VARCHAR(255) DEFAULT NULL, madre_nombre VARCHAR(255) DEFAULT NULL, madre_correo_electronico VARCHAR(255) DEFAULT NULL, madre_profesion VARCHAR(255) DEFAULT NULL, madre_lugar_trabajo VARCHAR(255) DEFAULT NULL, madre_direccion_trabajo VARCHAR(255) DEFAULT NULL, apoderado_es_padre TINYINT(1) DEFAULT NULL, apoderado_es_madre TINYINT(1) DEFAULT NULL, apoderado_vive_con_estudiante TINYINT(1) DEFAULT NULL, apoderado_nombre VARCHAR(255) DEFAULT NULL, apoderado_correo_electronico VARCHAR(255) DEFAULT NULL, apoderado_profesion VARCHAR(255) DEFAULT NULL, apoderado_lugar_trabajo VARCHAR(255) DEFAULT NULL, apoderado_direccion_trabajo VARCHAR(255) DEFAULT NULL, padres_profesan_religion TINYINT(1) DEFAULT NULL, padres_religion VARCHAR(255) DEFAULT NULL, quien_retira_nombre VARCHAR(255) DEFAULT NULL, observaciones LONGTEXT DEFAULT NULL, clinica_tiene_seguro TINYINT(1) DEFAULT NULL, clinica_institucion_seguro VARCHAR(255) DEFAULT NULL, clinica_telefono_institucion_seguro INT DEFAULT NULL, clinica_tiene_enfermedad_cuidado_especial TINYINT(1) DEFAULT NULL, clinica_enfermedad_cuidado_especial VARCHAR(255) DEFAULT NULL, clinica_recomendaciones LONGTEXT DEFAULT NULL, clinica_observaciones LONGTEXT DEFAULT NULL, INDEX IDX_15DF188587CB4A1F (curso_id), INDEX IDX_15DF1885AB8DC0F8 (nacionalidad_id), INDEX IDX_15DF188573AEFE7 (comuna_id), INDEX IDX_15DF18851CBB0FB8 (con_quien_vive_id), INDEX IDX_15DF1885E38E0B01 (padre_nivel_educacional_id), INDEX IDX_15DF1885D17C4D8C (madre_nivel_educacional_id), INDEX IDX_15DF1885F878C755 (apoderado_parentesco_id), INDEX IDX_15DF18851B423A39 (apoderado_nivel_educacional_id), INDEX IDX_15DF1885CF0D2834 (quien_retira_parentesco_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF188587CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885AB8DC0F8 FOREIGN KEY (nacionalidad_id) REFERENCES nacionalidad (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF188573AEFE7 FOREIGN KEY (comuna_id) REFERENCES comuna (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF18851CBB0FB8 FOREIGN KEY (con_quien_vive_id) REFERENCES con_quien_vive (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885E38E0B01 FOREIGN KEY (padre_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885D17C4D8C FOREIGN KEY (madre_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885F878C755 FOREIGN KEY (apoderado_parentesco_id) REFERENCES parentesco (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF18851B423A39 FOREIGN KEY (apoderado_nivel_educacional_id) REFERENCES nivel_educacional (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885CF0D2834 FOREIGN KEY (quien_retira_parentesco_id) REFERENCES parentesco (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE matricula');
    }
}
