<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnioRepository")
 */
class Anio
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
    private $numero;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vigente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Curso", mappedBy="anio")
     */
    private $cursos;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $matricula;

    public function __toString()
	{
	    return (string)$this->getNumero();
	}

    public function __construct()
    {
        $this->cursos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getVigente(): ?bool
    {
        return $this->vigente;
    }

    public function setVigente(bool $vigente): self
    {
        $this->vigente = $vigente;

        return $this;
    }

    /**
     * @return Collection|Curso[]
     */
    public function getCursos(): Collection
    {
        return $this->cursos;
    }

    public function addCurso(Curso $curso): self
    {
        if (!$this->cursos->contains($curso)) {
            $this->cursos[] = $curso;
            $curso->setAnio($this);
        }

        return $this;
    }

    public function removeCurso(Curso $curso): self
    {
        if ($this->cursos->contains($curso)) {
            $this->cursos->removeElement($curso);
            // set the owning side to null (unless already changed)
            if ($curso->getAnio() === $this) {
                $curso->setAnio(null);
            }
        }

        return $this;
    }

    public function getMatricula(): ?bool
    {
        return $this->matricula;
    }

    public function setMatricula(?bool $matricula): self
    {
        $this->matricula = $matricula;

        return $this;
    }
}
