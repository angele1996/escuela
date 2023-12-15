<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoPersonaRepository")
 */
class TipoPersona
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
     * @ORM\Column(type="boolean")
     */
    private $esResponsable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Persona", mappedBy="tipoPersona")
     */
    private $personas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
    }

    public function __toString()
	{
	    return $this->getNombre();
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

    public function getEsResponsable(): ?bool
    {
        return $this->esResponsable;
    }

    public function setEsResponsable(bool $esResponsable): self
    {
        $this->esResponsable = $esResponsable;

        return $this;
    }

    /**
     * @return Collection|Persona[]
     */
    public function getPersonas(): Collection
    {
        return $this->personas;
    }

    public function addPersona(Persona $persona): self
    {
        if (!$this->personas->contains($persona)) {
            $this->personas[] = $persona;
            $persona->setTipoPersona($this);
        }

        return $this;
    }

    public function removePersona(Persona $persona): self
    {
        if ($this->personas->contains($persona)) {
            $this->personas->removeElement($persona);
            // set the owning side to null (unless already changed)
            if ($persona->getTipoPersona() === $this) {
                $persona->setTipoPersona(null);
            }
        }

        return $this;
    }
}
