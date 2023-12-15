<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestamoRepository")
 */
class Prestamo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persona", inversedBy="prestamos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $persona;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaPrestamo;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaDevolucion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRenovacion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esDevuelto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaRealDevolucion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ejemplar", inversedBy="prestamos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ejemplar;

    public function __toString()
	{
	    return $this->getPersona().' '.$this->getItem();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    public function setPersona(?Persona $persona): self
    {
        $this->persona = $persona;

        return $this;
    }

    public function getFechaPrestamo(): ?\DateTimeInterface
    {
        return $this->fechaPrestamo;
    }

    public function setFechaPrestamo(\DateTimeInterface $fechaPrestamo): self
    {
        $this->fechaPrestamo = $fechaPrestamo;

        return $this;
    }

    public function getFechaDevolucion(): ?\DateTimeInterface
    {
        return $this->fechaDevolucion;
    }

    public function setFechaDevolucion(\DateTimeInterface $fechaDevolucion): self
    {
        $this->fechaDevolucion = $fechaDevolucion;

        return $this;
    }

    public function getFechaRenovacion(): ?\DateTimeInterface
    {
        return $this->fechaRenovacion;
    }

    public function setFechaRenovacion(?\DateTimeInterface $fechaRenovacion): self
    {
        $this->fechaRenovacion = $fechaRenovacion;

        return $this;
    }

    public function getEsDevuelto(): ?bool
    {
        return $this->esDevuelto;
    }

    public function setEsDevuelto(bool $esDevuelto): self
    {
        $this->esDevuelto = $esDevuelto;

        return $this;
    }

    public function getFechaRealDevolucion(): ?\DateTimeInterface
    {
        return $this->fechaRealDevolucion;
    }

    public function setFechaRealDevolucion(?\DateTimeInterface $fechaRealDevolucion): self
    {
        $this->fechaRealDevolucion = $fechaRealDevolucion;

        return $this;
    }

    public function getPlazo(): ?int
    {
        if($this->esDevuelto)
        {
            return 0;
        }
        else
        {
            $ahora = new \DateTime('now');
            $diferencia = $this->fechaDevolucion->add(new \DateInterval('P1D'))->diff($ahora);
            return $diferencia->days;
        }
    }

    public function getEjemplar(): ?Ejemplar
    {
        return $this->ejemplar;
    }

    public function setEjemplar(?Ejemplar $ejemplar): self
    {
        $this->ejemplar = $ejemplar;

        return $this;
    }
}
