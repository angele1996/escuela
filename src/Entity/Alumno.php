<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlumnoRepository")
 */
class Alumno
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Persona", inversedBy="alumnos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $persona;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Curso", inversedBy="alumnos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $curso;

    public function __toString()
	{
	    return (string)$this->getPersona().' '.(string)$this->getCurso();
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

    public function getCurso(): ?Curso
    {
        return $this->curso;
    }

    public function setCurso(?Curso $curso): self
    {
        $this->curso = $curso;

        return $this;
    }
}
