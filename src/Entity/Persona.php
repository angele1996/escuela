<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonaRepository")
 * @Vich\Uploadable 
 */
class Persona
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
    private $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $credencial;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Alumno", mappedBy="persona")
     */
    private $alumnos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="responsable")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoPersona", inversedBy="personas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoPersona;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Prestamo", mappedBy="persona")
     */
    private $prestamos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="persona_imagen", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    public function __toString()
	{
	    return $this->getNombres().' '.$this->getApellidos();
    }

    public function __construct()
    {
        $this->alumnos = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->prestamos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCredencial(): ?int
    {
        return $this->credencial;
    }

    public function setCredencial(?int $credencial): self
    {
        $this->credencial = $credencial;

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
            $alumno->setPersona($this);
        }

        return $this;
    }

    public function removeAlumno(Alumno $alumno): self
    {
        if ($this->alumnos->contains($alumno)) {
            $this->alumnos->removeElement($alumno);
            // set the owning side to null (unless already changed)
            if ($alumno->getPersona() === $this) {
                $alumno->setPersona(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setResponsable($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getResponsable() === $this) {
                $item->setResponsable(null);
            }
        }

        return $this;
    }

    public function getTipoPersona(): ?TipoPersona
    {
        return $this->tipoPersona;
    }

    public function setTipoPersona(?TipoPersona $tipoPersona): self
    {
        $this->tipoPersona = $tipoPersona;

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
            $prestamo->setPersona($this);
        }

        return $this;
    }

    public function removePrestamo(Prestamo $prestamo): self
    {
        if ($this->prestamos->contains($prestamo)) {
            $this->prestamos->removeElement($prestamo);
            // set the owning side to null (unless already changed)
            if ($prestamo->getPersona() === $this) {
                $prestamo->setPersona(null);
            }
        }

        return $this;
    }


    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
