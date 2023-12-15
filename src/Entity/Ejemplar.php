<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EjemplarRepository")
 */
class Ejemplar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Libro", inversedBy="ejemplars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $libro;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $codigo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaIncorporacion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EstadoLibro", inversedBy="ejemplars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $estadoLibro;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ubicacion", inversedBy="ejemplars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ubicacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $codigoImpreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaImpresionCodigo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Prestamo", mappedBy="ejemplar")
     */
    private $prestamos;

    public function __construct()
    {
        $this->prestamos = new ArrayCollection();
    }

    public function __toString()
	{
	    return $this->getCodigo().' '.$this->getLibro()->getNombre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibro(): ?Libro
    {
        return $this->libro;
    }

    public function setLibro(?Libro $libro): self
    {
        $this->libro = $libro;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(?int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getFechaIncorporacion(): ?\DateTimeInterface
    {
        return $this->fechaIncorporacion;
    }

    public function setFechaIncorporacion(?\DateTimeInterface $fechaIncorporacion): self
    {
        $this->fechaIncorporacion = $fechaIncorporacion;

        return $this;
    }

    public function getEstadoLibro(): ?EstadoLibro
    {
        return $this->estadoLibro;
    }

    public function setEstadoLibro(?EstadoLibro $estadoLibro): self
    {
        $this->estadoLibro = $estadoLibro;

        return $this;
    }

    public function getUbicacion(): ?Ubicacion
    {
        return $this->ubicacion;
    }

    public function setUbicacion(?Ubicacion $ubicacion): self
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    public function getCodigoImpreso(): ?bool
    {
        return $this->codigoImpreso;
    }

    public function setCodigoImpreso(bool $codigoImpreso): self
    {
        $this->codigoImpreso = $codigoImpreso;

        return $this;
    }

    public function getFechaImpresionCodigo(): ?\DateTimeInterface
    {
        return $this->fechaImpresionCodigo;
    }

    public function setFechaImpresionCodigo(?\DateTimeInterface $fechaImpresionCodigo): self
    {
        $this->fechaImpresionCodigo = $fechaImpresionCodigo;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection|Prestamo[]
     */
    public function getPrestamos(): Collection
    {
        return $this->prestamos;
    }

    public function addPrestamo(Prestamo $prestamo): self
    {
        if (!$this->prestamos->contains($prestamo)) {
            $this->prestamos[] = $prestamo;
            $prestamo->setEjemplar($this);
        }

        return $this;
    }

    public function removePrestamo(Prestamo $prestamo): self
    {
        if ($this->prestamos->contains($prestamo)) {
            $this->prestamos->removeElement($prestamo);
            // set the owning side to null (unless already changed)
            if ($prestamo->getEjemplar() === $this) {
                $prestamo->setEjemplar(null);
            }
        }

        return $this;
    }
}
