<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoItemRepository")
 */
class TipoItem
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
    private $esPrestable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="tipoItem")
     */
    private $items;

    /**
     * @ORM\Column(type="smallint")
     */
    private $diasPrestamo;

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

    public function getEsPrestable(): ?bool
    {
        return $this->esPrestable;
    }

    public function setEsPrestable(bool $esPrestable): self
    {
        $this->esPrestable = $esPrestable;

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
            $item->setTipoItem($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getTipoItem() === $this) {
                $item->setTipoItem(null);
            }
        }

        return $this;
    }

    public function getDiasPrestamo(): ?int
    {
        return $this->diasPrestamo;
    }

    public function setDiasPrestamo(int $diasPrestamo): self
    {
        $this->diasPrestamo = $diasPrestamo;

        return $this;
    }
}
