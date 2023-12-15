<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UbicacionRepository")
 */
class Ubicacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ejemplar", mappedBy="ubicacion")
     */
    private $ejemplars;

    public function __toString()
	{
	    return $this->getNombre();
    }

    public function __construct()
    {
        $this->ejemplars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Ejemplar[]
     */
    public function getEjemplars(): Collection
    {
        return $this->ejemplars;
    }

    public function addEjemplar(Ejemplar $ejemplar): self
    {
        if (!$this->ejemplars->contains($ejemplar)) {
            $this->ejemplars[] = $ejemplar;
            $ejemplar->setUbicacion($this);
        }

        return $this;
    }

    public function removeEjemplar(Ejemplar $ejemplar): self
    {
        if ($this->ejemplars->contains($ejemplar)) {
            $this->ejemplars->removeElement($ejemplar);
            // set the owning side to null (unless already changed)
            if ($ejemplar->getUbicacion() === $this) {
                $ejemplar->setUbicacion(null);
            }
        }

        return $this;
    }
}
