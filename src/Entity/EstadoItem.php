<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstadoItemRepository")
 */
class EstadoItem
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
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="estadoItem")
     */
    private $items;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esInventario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esBiblioteca;

    public function __toString()
	{
	    return $this->getNombre();
    }

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
            $item->setEstadoItem($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getEstadoItem() === $this) {
                $item->setEstadoItem(null);
            }
        }

        return $this;
    }

    public function getEsInventario(): ?bool
    {
        return $this->esInventario;
    }

    public function setEsInventario(bool $esInventario): self
    {
        $this->esInventario = $esInventario;

        return $this;
    }

    public function getEsBiblioteca(): ?bool
    {
        return $this->esBiblioteca;
    }

    public function setEsBiblioteca(bool $esBiblioteca): self
    {
        $this->esBiblioteca = $esBiblioteca;

        return $this;
    }
}
