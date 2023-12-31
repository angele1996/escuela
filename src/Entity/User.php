<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisoAdministrador;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisoBiblioteca;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisoInventario;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $permisoMatricula;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPermisoAdministrador(): ?bool
    {
        return $this->permisoAdministrador;
    }

    public function setPermisoAdministrador(?bool $permisoAdministrador): self
    {
        $this->permisoAdministrador = $permisoAdministrador;

        return $this;
    }

    public function getPermisoBiblioteca(): ?bool
    {
        return $this->permisoBiblioteca;
    }

    public function setPermisoBiblioteca(?bool $permisoBiblioteca): self
    {
        $this->permisoBiblioteca = $permisoBiblioteca;

        return $this;
    }

    public function getPermisoInventario(): ?bool
    {
        return $this->permisoInventario;
    }

    public function setPermisoInventario(?bool $permisoInventario): self
    {
        $this->permisoInventario = $permisoInventario;

        return $this;
    }

    public function getPermisoMatricula(): ?bool
    {
        return $this->permisoMatricula;
    }

    public function setPermisoMatricula(?bool $permisoMatricula): self
    {
        $this->permisoMatricula = $permisoMatricula;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
