<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfiguracionCodigoRepository")
 */
class ConfiguracionCodigo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $margenIzquierda;

    /**
     * @ORM\Column(type="smallint")
     */
    private $margenDerecha;

    /**
     * @ORM\Column(type="smallint")
     */
    private $margenSuperior;

    /**
     * @ORM\Column(type="smallint")
     */
    private $margenInferior;

    /**
     * @ORM\Column(type="smallint")
     */
    private $largoPagina;

    /**
     * @ORM\Column(type="smallint")
     */
    private $anchoPagina;

    /**
     * @ORM\Column(type="smallint")
     */
    private $largoEtiqueta;

    /**
     * @ORM\Column(type="smallint")
     */
    private $anchoEtiqueta;

    /**
     * @ORM\Column(type="smallint")
     */
    private $espacioFilas;

    /**
     * @ORM\Column(type="smallint")
     */
    private $espacioColumnas;

    /**
     * @ORM\Column(type="smallint")
     */
    private $fuente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMargenIzquierda(): ?int
    {
        return $this->margenIzquierda;
    }

    public function setMargenIzquierda(int $margenIzquierda): self
    {
        $this->margenIzquierda = $margenIzquierda;

        return $this;
    }

    public function getMargenDerecha(): ?int
    {
        return $this->margenDerecha;
    }

    public function setMargenDerecha(int $margenDerecha): self
    {
        $this->margenDerecha = $margenDerecha;

        return $this;
    }

    public function getMargenSuperior(): ?int
    {
        return $this->margenSuperior;
    }

    public function setMargenSuperior(int $margenSuperior): self
    {
        $this->margenSuperior = $margenSuperior;

        return $this;
    }

    public function getMargenInferior(): ?int
    {
        return $this->margenInferior;
    }

    public function setMargenInferior(int $margenInferior): self
    {
        $this->margenInferior = $margenInferior;

        return $this;
    }

    public function getLargoPagina(): ?int
    {
        return $this->largoPagina;
    }

    public function setLargoPagina(int $largoPagina): self
    {
        $this->largoPagina = $largoPagina;

        return $this;
    }

    public function getAnchoPagina(): ?int
    {
        return $this->anchoPagina;
    }

    public function setAnchoPagina(int $anchoPagina): self
    {
        $this->anchoPagina = $anchoPagina;

        return $this;
    }

    public function getLargoEtiqueta(): ?int
    {
        return $this->largoEtiqueta;
    }

    public function setLargoEtiqueta(int $largoEtiqueta): self
    {
        $this->largoEtiqueta = $largoEtiqueta;

        return $this;
    }

    public function getAnchoEtiqueta(): ?int
    {
        return $this->anchoEtiqueta;
    }

    public function setAnchoEtiqueta(int $anchoEtiqueta): self
    {
        $this->anchoEtiqueta = $anchoEtiqueta;

        return $this;
    }

    public function getEspacioFilas(): ?int
    {
        return $this->espacioFilas;
    }

    public function setEspacioFilas(int $espacioFilas): self
    {
        $this->espacioFilas = $espacioFilas;

        return $this;
    }

    public function getEspacioColumnas(): ?int
    {
        return $this->espacioColumnas;
    }

    public function setEspacioColumnas(int $espacioColumnas): self
    {
        $this->espacioColumnas = $espacioColumnas;

        return $this;
    }

    public function getFuente(): ?int
    {
        return $this->fuente;
    }

    public function setFuente(int $fuente): self
    {
        $this->fuente = $fuente;

        return $this;
    }
}
