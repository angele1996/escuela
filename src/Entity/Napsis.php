<?php

namespace App\Entity;

use App\Repository\NapsisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NapsisRepository::class)
 */
class Napsis
{
      /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellidoPaterno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apellidoMaterno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombres;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaNacimiento;

    /**
     * @ORM\ManyToOne(targetEntity=Nacionalidad::class)
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domicilio;

    /**
     * @ORM\ManyToOne(targetEntity=Comuna::class)
     */
    private $comuna;
  
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroTelefonoContacto1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreNombre;

    /**
     * @ORM\ManyToOne(targetEntity=NivelEducacional::class)
     */
    private $padreNivelEducacional;
   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreNombre;

    /**
     * @ORM\ManyToOne(targetEntity=NivelEducacional::class)
     */
    private $madreNivelEducacional;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $apoderadoParentesco;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoNombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoCorreoElectronico;

    /**
     * @ORM\ManyToOne(targetEntity=NivelEducacional::class)
     */
    private $apoderadoNivelEducacional;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoProfesion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $padresProfesanReligion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padresReligion;

    /**
     * @ORM\ManyToOne(targetEntity=Genero::class)
     */
    private $genero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $correoElectronico;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apoderadoTelefono;

    /**
     * @ORM\ManyToOne(targetEntity=Genero::class)
     */
    private $apoderadoGenero;

    /**
     * @ORM\ManyToOne(targetEntity=EstadoCivil::class)
     */
    private $apoderadoEstadoCivil;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $apoderadoFechaNacimiento;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreDireccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreDireccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoDireccion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreRut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreRut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoRut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRut(): ?string
    {
        return $this->rut;
    }

    public function setRut(string $rut): self
    {
        $this->rut = $rut;

        return $this;
    }

    public function getApellidoPaterno(): ?string
    {
        return $this->apellidoPaterno;
    }

    public function setApellidoPaterno(?string $apellidoPaterno): self
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }

    public function getApellidoMaterno(): ?string
    {
        return $this->apellidoMaterno;
    }

    public function setApellidoMaterno(?string $apellidoMaterno): self
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(?string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(?\DateTimeInterface $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getNacionalidad(): ?Nacionalidad
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?Nacionalidad $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getDomicilio(): ?string
    {
        return $this->domicilio;
    }

    public function setDomicilio(?string $domicilio): self
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    public function getComuna(): ?Comuna
    {
        return $this->comuna;
    }

    public function setComuna(?Comuna $comuna): self
    {
        $this->comuna = $comuna;

        return $this;
    }

    public function getNumeroTelefonoContacto1(): ?int
    {
        return $this->numeroTelefonoContacto1;
    }

    public function setNumeroTelefonoContacto1(?int $numeroTelefonoContacto1): self
    {
        $this->numeroTelefonoContacto1 = $numeroTelefonoContacto1;

        return $this;
    }

    public function getPadreNombre(): ?string
    {
        return $this->padreNombre;
    }

    public function setPadreNombre(?string $padreNombre): self
    {
        $this->padreNombre = $padreNombre;

        return $this;
    }

    public function getPadreNivelEducacional(): ?NivelEducacional
    {
        return $this->padreNivelEducacional;
    }

    public function setPadreNivelEducacional(?NivelEducacional $padreNivelEducacional): self
    {
        $this->padreNivelEducacional = $padreNivelEducacional;

        return $this;
    }

    public function getMadreNombre(): ?string
    {
        return $this->madreNombre;
    }

    public function setMadreNombre(?string $madreNombre): self
    {
        $this->madreNombre = $madreNombre;

        return $this;
    }

    public function getMadreNivelEducacional(): ?NivelEducacional
    {
        return $this->madreNivelEducacional;
    }

    public function setMadreNivelEducacional(?NivelEducacional $madreNivelEducacional): self
    {
        $this->madreNivelEducacional = $madreNivelEducacional;

        return $this;
    }

    public function getApoderadoParentesco(): ?Parentesco
    {
        return $this->apoderadoParentesco;
    }

    public function setApoderadoParentesco(?Parentesco $apoderadoParentesco): self
    {
        $this->apoderadoParentesco = $apoderadoParentesco;

        return $this;
    }

    public function getApoderadoNombre(): ?string
    {
        return $this->apoderadoNombre;
    }

    public function setApoderadoNombre(?string $apoderadoNombre): self
    {
        $this->apoderadoNombre = $apoderadoNombre;

        return $this;
    }

    public function getApoderadoCorreoElectronico(): ?string
    {
        return $this->apoderadoCorreoElectronico;
    }

    public function setApoderadoCorreoElectronico(?string $apoderadoCorreoElectronico): self
    {
        $this->apoderadoCorreoElectronico = $apoderadoCorreoElectronico;

        return $this;
    }

    public function getApoderadoNivelEducacional(): ?NivelEducacional
    {
        return $this->apoderadoNivelEducacional;
    }

    public function setApoderadoNivelEducacional(?NivelEducacional $apoderadoNivelEducacional): self
    {
        $this->apoderadoNivelEducacional = $apoderadoNivelEducacional;

        return $this;
    }

    public function getApoderadoProfesion(): ?string
    {
        return $this->apoderadoProfesion;
    }

    public function setApoderadoProfesion(?string $apoderadoProfesion): self
    {
        $this->apoderadoProfesion = $apoderadoProfesion;

        return $this;
    }

    public function getPadresProfesanReligion(): ?bool
    {
        return $this->padresProfesanReligion;
    }

    public function setPadresProfesanReligion(?bool $padresProfesanReligion): self
    {
        $this->padresProfesanReligion = $padresProfesanReligion;

        return $this;
    }

    public function getPadresReligion(): ?string
    {
        return $this->padresReligion;
    }

    public function setPadresReligion(?string $padresReligion): self
    {
        $this->padresReligion = $padresReligion;

        return $this;
    }

    public function getGenero(): ?Genero
    {
        return $this->genero;
    }

    public function setGenero(?Genero $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getCorreoElectronico(): ?string
    {
        return $this->correoElectronico;
    }

    public function setCorreoElectronico(?string $correoElectronico): self
    {
        $this->correoElectronico = $correoElectronico;

        return $this;
    }

    public function getApoderadoTelefono(): ?int
    {
        return $this->apoderadoTelefono;
    }

    public function setApoderadoTelefono(?int $apoderadoTelefono): self
    {
        $this->apoderadoTelefono = $apoderadoTelefono;

        return $this;
    }

    public function getApoderadoGenero(): ?Genero
    {
        return $this->apoderadoGenero;
    }

    public function setApoderadoGenero(?Genero $apoderadoGenero): self
    {
        $this->apoderadoGenero = $apoderadoGenero;

        return $this;
    }

    public function getApoderadoEstadoCivil(): ?EstadoCivil
    {
        return $this->apoderadoEstadoCivil;
    }

    public function setApoderadoEstadoCivil(?EstadoCivil $apoderadoEstadoCivil): self
    {
        $this->apoderadoEstadoCivil = $apoderadoEstadoCivil;

        return $this;
    }

    public function getApoderadoFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->apoderadoFechaNacimiento;
    }

    public function setApoderadoFechaNacimiento(?\DateTimeInterface $apoderadoFechaNacimiento): self
    {
        $this->apoderadoFechaNacimiento = $apoderadoFechaNacimiento;

        return $this;
    }

    public function getPadreDireccion(): ?string
    {
        return $this->padreDireccion;
    }

    public function setPadreDireccion(?string $padreDireccion): self
    {
        $this->padreDireccion = $padreDireccion;

        return $this;
    }

    public function getMadreDireccion(): ?string
    {
        return $this->madreDireccion;
    }

    public function setMadreDireccion(?string $madreDireccion): self
    {
        $this->madreDireccion = $madreDireccion;

        return $this;
    }

    public function getApoderadoDireccion(): ?string
    {
        return $this->apoderadoDireccion;
    }

    public function setApoderadoDireccion(?string $apoderadoDireccion): self
    {
        $this->apoderadoDireccion = $apoderadoDireccion;

        return $this;
    }

    public function getPadreRut(): ?string
    {
        return $this->padreRut;
    }

    public function setPadreRut(?string $padreRut): self
    {
        $this->padreRut = $padreRut;

        return $this;
    }

    public function getMadreRut(): ?string
    {
        return $this->madreRut;
    }

    public function setMadreRut(?string $madreRut): self
    {
        $this->madreRut = $madreRut;

        return $this;
    }

    public function getApoderadoRut(): ?string
    {
        return $this->apoderadoRut;
    }

    public function setApoderadoRut(?string $apoderadoRut): self
    {
        $this->apoderadoRut = $apoderadoRut;

        return $this;
    }
}
