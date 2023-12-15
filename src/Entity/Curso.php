<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CursoRepository")
 */
class Curso
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Anio", inversedBy="cursos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $anio;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Alumno", mappedBy="curso")
     */
    private $alumnos;

    /**
     * @ORM\OneToMany(targetEntity=Matricula::class, mappedBy="curso")
     */
    private $matriculas;

    public function __toString()
	{
	    return $this->getNombre().' '.(string)$this->getAnio();
    }
    
    public function __construct()
    {
        $this->alumnos = new ArrayCollection();
        $this->matriculas = new ArrayCollection();
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

    public function getAnio(): ?Anio
    {
        return $this->anio;
    }

    public function setAnio(?Anio $anio): self
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * @return Collection|Alumno[]
     */
    public function getAlumnos(): Collection
    {
        return $this->alumnos;
    }

    public function addAlumno(Alumno $alumno): self
    {
        if (!$this->alumnos->contains($alumno)) {
            $this->alumnos[] = $alumno;
            $alumno->setCurso($this);
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        if ($this->alumnos->contains($alumno)) {
            $this->alumnos->removeElement($alumno);
            // set the owning side to null (unless already changed)
            if ($alumno->getCurso() === $this) {
                $alumno->setCurso(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Matricula[]
     */
    public function getMatriculas(): Collection
    {
        return $this->matriculas;
    }

    public function addMatricula(Matricula $matricula): self
    {
        if (!$this->matriculas->contains($matricula)) {
            $this->matriculas[] = $matricula;
            $matricula->setCurso($this);
        }

        return $this;
    }

    public function removeMatricula(Matricula $matricula): self
    {
        if ($this->matriculas->removeElement($matricula)) {
            // set the owning side to null (unless already changed)
            if ($matricula->getCurso() === $this) {
                $matricula->setCurso(null);
            }
        }

        return $this;
    }
}
