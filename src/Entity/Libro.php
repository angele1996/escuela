<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Entity(repositoryClass="App\Repository\LibroRepository")
 * @Vich\Uploadable 
 */
class Libro
{
    public const DIAS_DE_PRESTAMO = 5;

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
     * @ORM\Column(type="string", length=255)
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Editorial", inversedBy="libros")
     */
    private $editorial;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ejemplar", mappedBy="libro")
     */
    private $ejemplars;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Autor", inversedBy="libros")
     */
    private $autors;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="libro_imagen", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;    

    public function __toString()
	{
	    return $this->getNombre();
    }

    public function __construct()
    {
        $this->ejemplars = new ArrayCollection();
        $this->autors = new ArrayCollection();
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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getEditorial(): ?Editorial
    {
        return $this->editorial;
    }

    public function setEditorial(?Editorial $editorial): self
    {
        $this->editorial = $editorial;

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
            $ejemplar->setLibro($this);
        }

        return $this;
    }

    public function removeEjemplar(Ejemplar $ejemplar): self
    {
        if ($this->ejemplars->contains($ejemplar)) {
            $this->ejemplars->removeElement($ejemplar);
            // set the owning side to null (unless already changed)
            if ($ejemplar->getLibro() === $this) {
                $ejemplar->setLibro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Autor[]
     */
    public function getAutors(): Collection
    {
        return $this->autors;
    }

    public function addAutor(Autor $autor): self
    {
        if (!$this->autors->contains($autor)) {
            $this->autors[] = $autor;
        }

        return $this;
    }

    public function removeAutor(Autor $autor): self
    {
        if ($this->autors->contains($autor)) {
            $this->autors->removeElement($autor);
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

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }       
}
