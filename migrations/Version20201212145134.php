<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212145134 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alumno (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, curso_id INT NOT NULL, INDEX IDX_1435D52DF5F88DB9 (persona_id), INDEX IDX_1435D52D87CB4A1F (curso_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anio (id INT AUTO_INCREMENT NOT NULL, numero SMALLINT NOT NULL, vigente TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autor (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuracion_codigo (id INT AUTO_INCREMENT NOT NULL, margen_izquierda SMALLINT NOT NULL, margen_derecha SMALLINT NOT NULL, margen_superior SMALLINT NOT NULL, margen_inferior SMALLINT NOT NULL, largo_pagina SMALLINT NOT NULL, ancho_pagina SMALLINT NOT NULL, largo_etiqueta SMALLINT NOT NULL, ancho_etiqueta SMALLINT NOT NULL, espacio_filas SMALLINT NOT NULL, espacio_columnas SMALLINT NOT NULL, fuente SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curso (id INT AUTO_INCREMENT NOT NULL, anio_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_CA3B40ECEC34184E (anio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editorial (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ejemplar (id INT AUTO_INCREMENT NOT NULL, libro_id INT NOT NULL, estado_libro_id INT NOT NULL, ubicacion_id INT NOT NULL, codigo INT DEFAULT NULL, fecha_incorporacion DATE DEFAULT NULL, codigo_impreso TINYINT(1) NOT NULL, fecha_impresion_codigo DATETIME DEFAULT NULL, activo TINYINT(1) NOT NULL, INDEX IDX_36B9726C0238522 (libro_id), INDEX IDX_36B972631D536CF (estado_libro_id), INDEX IDX_36B972657E759E8 (ubicacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estado_item (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, es_inventario TINYINT(1) NOT NULL, es_biblioteca TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estado_libro (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estanteria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, tipo_item_id INT NOT NULL, estado_item_id INT NOT NULL, responsable_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, codigo INT DEFAULT NULL, marca VARCHAR(255) DEFAULT NULL, modelo VARCHAR(255) DEFAULT NULL, observaciones LONGTEXT DEFAULT NULL, fecha_incorporacion DATE DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, autor VARCHAR(255) DEFAULT NULL, editorial VARCHAR(255) DEFAULT NULL, edad SMALLINT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1F1B251E19673FE (tipo_item_id), INDEX IDX_1F1B251E303EFD8F (estado_item_id), INDEX IDX_1F1B251E53C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE libro (id INT AUTO_INCREMENT NOT NULL, editorial_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, observaciones VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, activo TINYINT(1) NOT NULL, INDEX IDX_5799AD2BBAF1A24D (editorial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE libro_autor (libro_id INT NOT NULL, autor_id INT NOT NULL, INDEX IDX_F7588AEFC0238522 (libro_id), INDEX IDX_F7588AEF14D45BBE (autor_id), PRIMARY KEY(libro_id, autor_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, tipo_persona_id INT NOT NULL, nombres VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, credencial INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_51E5B69B647E8F91 (tipo_persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestamo (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, ejemplar_id INT NOT NULL, fecha_prestamo DATETIME NOT NULL, fecha_devolucion DATE NOT NULL, fecha_renovacion DATETIME DEFAULT NULL, es_devuelto TINYINT(1) NOT NULL, fecha_real_devolucion DATETIME DEFAULT NULL, INDEX IDX_F4D874F2F5F88DB9 (persona_id), INDEX IDX_F4D874F2BEE15B6C (ejemplar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_item (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, es_prestable TINYINT(1) NOT NULL, dias_prestamo SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_persona (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, es_responsable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ubicacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alumno ADD CONSTRAINT FK_1435D52DF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE alumno ADD CONSTRAINT FK_1435D52D87CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE curso ADD CONSTRAINT FK_CA3B40ECEC34184E FOREIGN KEY (anio_id) REFERENCES anio (id)');
        $this->addSql('ALTER TABLE ejemplar ADD CONSTRAINT FK_36B9726C0238522 FOREIGN KEY (libro_id) REFERENCES libro (id)');
        $this->addSql('ALTER TABLE ejemplar ADD CONSTRAINT FK_36B972631D536CF FOREIGN KEY (estado_libro_id) REFERENCES estado_libro (id)');
        $this->addSql('ALTER TABLE ejemplar ADD CONSTRAINT FK_36B972657E759E8 FOREIGN KEY (ubicacion_id) REFERENCES ubicacion (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E19673FE FOREIGN KEY (tipo_item_id) REFERENCES tipo_item (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E303EFD8F FOREIGN KEY (estado_item_id) REFERENCES estado_item (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E53C59D72 FOREIGN KEY (responsable_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE libro ADD CONSTRAINT FK_5799AD2BBAF1A24D FOREIGN KEY (editorial_id) REFERENCES editorial (id)');
        $this->addSql('ALTER TABLE libro_autor ADD CONSTRAINT FK_F7588AEFC0238522 FOREIGN KEY (libro_id) REFERENCES libro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE libro_autor ADD CONSTRAINT FK_F7588AEF14D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B647E8F91 FOREIGN KEY (tipo_persona_id) REFERENCES tipo_persona (id)');
        $this->addSql('ALTER TABLE prestamo ADD CONSTRAINT FK_F4D874F2F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE prestamo ADD CONSTRAINT FK_F4D874F2BEE15B6C FOREIGN KEY (ejemplar_id) REFERENCES ejemplar (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso DROP FOREIGN KEY FK_CA3B40ECEC34184E');
        $this->addSql('ALTER TABLE libro_autor DROP FOREIGN KEY FK_F7588AEF14D45BBE');
        $this->addSql('ALTER TABLE alumno DROP FOREIGN KEY FK_1435D52D87CB4A1F');
        $this->addSql('ALTER TABLE libro DROP FOREIGN KEY FK_5799AD2BBAF1A24D');
        $this->addSql('ALTER TABLE prestamo DROP FOREIGN KEY FK_F4D874F2BEE15B6C');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E303EFD8F');
        $this->addSql('ALTER TABLE ejemplar DROP FOREIGN KEY FK_36B972631D536CF');
        $this->addSql('ALTER TABLE ejemplar DROP FOREIGN KEY FK_36B9726C0238522');
        $this->addSql('ALTER TABLE libro_autor DROP FOREIGN KEY FK_F7588AEFC0238522');
        $this->addSql('ALTER TABLE alumno DROP FOREIGN KEY FK_1435D52DF5F88DB9');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E53C59D72');
        $this->addSql('ALTER TABLE prestamo DROP FOREIGN KEY FK_F4D874F2F5F88DB9');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E19673FE');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B647E8F91');
        $this->addSql('ALTER TABLE ejemplar DROP FOREIGN KEY FK_36B972657E759E8');
        $this->addSql('DROP TABLE alumno');
        $this->addSql('DROP TABLE anio');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE configuracion_codigo');
        $this->addSql('DROP TABLE curso');
        $this->addSql('DROP TABLE editorial');
        $this->addSql('DROP TABLE ejemplar');
        $this->addSql('DROP TABLE estado_item');
        $this->addSql('DROP TABLE estado_libro');
        $this->addSql('DROP TABLE estanteria');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE libro');
        $this->addSql('DROP TABLE libro_autor');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE prestamo');
        $this->addSql('DROP TABLE tipo_item');
        $this->addSql('DROP TABLE tipo_persona');
        $this->addSql('DROP TABLE ubicacion');
    }
}
