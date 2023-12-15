<?php

namespace App\Entity;

use App\Repository\MatriculaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MatriculaRepository::class)
 * @Vich\Uploadable
 */
class Matricula
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaRegistro;

    /**
     * @ORM\ManyToOne(targetEntity=Curso::class, inversedBy="matriculas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $curso;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ciudadNacimiento;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreTelefonoContacto1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroTelefonoContacto1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreTelefonoContacto2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroTelefonoContacto2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreTelefonoContacto3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroTelefonoContacto3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $colegioProcedencia;

    /**
     * @ORM\ManyToOne(targetEntity=ConQuienVive::class)
     */
    private $conQuienVive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repiteCurso;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $necesidadesEducativasEspeciales;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreNombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreCorreoElectronico;

    /**
     * @ORM\ManyToOne(targetEntity=NivelEducacional::class)
     */
    private $padreNivelEducacional;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreProfesion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreLugarTrabajo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padreDireccionTrabajo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreNombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreCorreoElectronico;

    /**
     * @ORM\ManyToOne(targetEntity=NivelEducacional::class)
     */
    private $madreNivelEducacional;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreProfesion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreLugarTrabajo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $madreDireccionTrabajo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $apoderadoEsPadre;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $apoderadoEsMadre;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $apoderadoParentesco;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $apoderadoViveConEstudiante;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoLugarTrabajo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apoderadoDireccionTrabajo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $padresProfesanReligion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $padresReligion;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $quienRetiraParentesco;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quienRetiraNombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clinicaTieneSeguro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clinicaInstitucionSeguro;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $clinicaTelefonoInstitucionSeguro;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clinicaTieneEnfermedadCuidadoEspecial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clinicaEnfermedadCuidadoEspecial;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $clinicaRecomendaciones;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $clinicaObservaciones;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $solicitanteNombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $solicitanteRut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $solicitanteCorreoElectronico;

    /**
     * @ORM\ManyToOne(targetEntity=Genero::class)
     */
    private $genero;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telefono;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $padreTelefono;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $madreTelefono;

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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pdf;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $parentescoTelefonoContacto1;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $parentescoTelefonoContacto2;

    /**
     * @ORM\ManyToOne(targetEntity=Parentesco::class)
     */
    private $parentescoTelefonoContacto3;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $matriculaCompletada;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matriculaUsuario;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $matriculaFecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fotoApoderado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fotoEstudiante;

    /**
     * @Vich\UploadableField(mapping="matricula_apoderado", fileNameProperty="fotoApoderado")
     * @var File
     */
    private $fotoApoderadoFile;

    /**
     * @Vich\UploadableField(mapping="matricula_estudiante", fileNameProperty="fotoEstudiante")
     * @var File
     */
    private $fotoEstudianteFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $necesidadesEducativasEspecialesCual;

    /**
     * @ORM\ManyToOne(targetEntity=Religion::class)
     */
    private $padresSeleccionReligion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activo;
 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaRegistro(): ?\DateTimeInterface
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(\DateTimeInterface $fechaRegistro): self
    {
        $this->fechaRegistro = $fechaRegistro;

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

    public function getCiudadNacimiento(): ?string
    {
        return $this->ciudadNacimiento;
    }

    public function setCiudadNacimiento(?string $ciudadNacimiento): self
    {
        $this->ciudadNacimiento = $ciudadNacimiento;

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

    public function getNombreTelefonoContacto1(): ?string
    {
        return $this->nombreTelefonoContacto1;
    }

    public function setNombreTelefonoContacto1(?string $nombreTelefonoContacto1): self
    {
        $this->nombreTelefonoContacto1 = $nombreTelefonoContacto1;

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

    public function getNombreTelefonoContacto2(): ?string
    {
        return $this->nombreTelefonoContacto2;
    }

    public function setNombreTelefonoContacto2(?string $nombreTelefonoContacto2): self
    {
        $this->nombreTelefonoContacto2 = $nombreTelefonoContacto2;

        return $this;
    }

    public function getNumeroTelefonoContacto2(): ?int
    {
        return $this->numeroTelefonoContacto2;
    }

    public function setNumeroTelefonoContacto2(?int $numeroTelefonoContacto2): self
    {
        $this->numeroTelefonoContacto2 = $numeroTelefonoContacto2;

        return $this;
    }

    public function getNombreTelefonoContacto3(): ?string
    {
        return $this->nombreTelefonoContacto3;
    }

    public function setNombreTelefonoContacto3(?string $nombreTelefonoContacto3): self
    {
        $this->nombreTelefonoContacto3 = $nombreTelefonoContacto3;

        return $this;
    }

    public function getNumeroTelefonoContacto3(): ?int
    {
        return $this->numeroTelefonoContacto3;
    }

    public function setNumeroTelefonoContacto3(?int $numeroTelefonoContacto3): self
    {
        $this->numeroTelefonoContacto3 = $numeroTelefonoContacto3;

        return $this;
    }

    public function getColegioProcedencia(): ?string
    {
        return $this->colegioProcedencia;
    }

    public function setColegioProcedencia(?string $colegioProcedencia): self
    {
        $this->colegioProcedencia = $colegioProcedencia;

        return $this;
    }

    public function getConQuienVive(): ?ConQuienVive
    {
        return $this->conQuienVive;
    }

    public function setConQuienVive(?ConQuienVive $conQuienVive): self
    {
        $this->conQuienVive = $conQuienVive;

        return $this;
    }

    public function getRepiteCurso(): ?bool
    {
        return $this->repiteCurso;
    }

    public function setRepiteCurso(?bool $repiteCurso): self
    {
        $this->repiteCurso = $repiteCurso;

        return $this;
    }

    public function getNecesidadesEducativasEspeciales(): ?bool
    {
        return $this->necesidadesEducativasEspeciales;
    }

    public function setNecesidadesEducativasEspeciales(?bool $necesidadesEducativasEspeciales): self
    {
        $this->necesidadesEducativasEspeciales = $necesidadesEducativasEspeciales;

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

    public function getPadreCorreoElectronico(): ?string
    {
        return $this->padreCorreoElectronico;
    }

    public function setPadreCorreoElectronico(?string $padreCorreoElectronico): self
    {
        $this->padreCorreoElectronico = $padreCorreoElectronico;

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

    public function getPadreProfesion(): ?string
    {
        return $this->padreProfesion;
    }

    public function setPadreProfesion(?string $padreProfesion): self
    {
        $this->padreProfesion = $padreProfesion;

        return $this;
    }

    public function getPadreLugarTrabajo(): ?string
    {
        return $this->padreLugarTrabajo;
    }

    public function setPadreLugarTrabajo(?string $padreLugarTrabajo): self
    {
        $this->padreLugarTrabajo = $padreLugarTrabajo;

        return $this;
    }

    public function getPadreDireccionTrabajo(): ?string
    {
        return $this->padreDireccionTrabajo;
    }

    public function setPadreDireccionTrabajo(?string $padreDireccionTrabajo): self
    {
        $this->padreDireccionTrabajo = $padreDireccionTrabajo;

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

    public function getMadreCorreoElectronico(): ?string
    {
        return $this->madreCorreoElectronico;
    }

    public function setMadreCorreoElectronico(?string $madreCorreoElectronico): self
    {
        $this->madreCorreoElectronico = $madreCorreoElectronico;

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

    public function getMadreProfesion(): ?string
    {
        return $this->madreProfesion;
    }

    public function setMadreProfesion(?string $madreProfesion): self
    {
        $this->madreProfesion = $madreProfesion;

        return $this;
    }

    public function getMadreLugarTrabajo(): ?string
    {
        return $this->madreLugarTrabajo;
    }

    public function setMadreLugarTrabajo(?string $madreLugarTrabajo): self
    {
        $this->madreLugarTrabajo = $madreLugarTrabajo;

        return $this;
    }

    public function getMadreDireccionTrabajo(): ?string
    {
        return $this->madreDireccionTrabajo;
    }

    public function setMadreDireccionTrabajo(?string $madreDireccionTrabajo): self
    {
        $this->madreDireccionTrabajo = $madreDireccionTrabajo;

        return $this;
    }

    public function getApoderadoEsPadre(): ?bool
    {
        return $this->apoderadoEsPadre;
    }

    public function setApoderadoEsPadre(?bool $apoderadoEsPadre): self
    {
        $this->apoderadoEsPadre = $apoderadoEsPadre;

        return $this;
    }

    public function getApoderadoEsMadre(): ?bool
    {
        return $this->apoderadoEsMadre;
    }

    public function setApoderadoEsMadre(?bool $apoderadoEsMadre): self
    {
        $this->apoderadoEsMadre = $apoderadoEsMadre;

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

    public function getApoderadoViveConEstudiante(): ?bool
    {
        return $this->apoderadoViveConEstudiante;
    }

    public function setApoderadoViveConEstudiante(?bool $apoderadoViveConEstudiante): self
    {
        $this->apoderadoViveConEstudiante = $apoderadoViveConEstudiante;

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

    public function getApoderadoLugarTrabajo(): ?string
    {
        return $this->apoderadoLugarTrabajo;
    }

    public function setApoderadoLugarTrabajo(?string $apoderadoLugarTrabajo): self
    {
        $this->apoderadoLugarTrabajo = $apoderadoLugarTrabajo;

        return $this;
    }

    public function getApoderadoDireccionTrabajo(): ?string
    {
        return $this->apoderadoDireccionTrabajo;
    }

    public function setApoderadoDireccionTrabajo(?string $apoderadoDireccionTrabajo): self
    {
        $this->apoderadoDireccionTrabajo = $apoderadoDireccionTrabajo;

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

    public function getQuienRetiraParentesco(): ?Parentesco
    {
        return $this->quienRetiraParentesco;
    }

    public function setQuienRetiraParentesco(?Parentesco $quienRetiraParentesco): self
    {
        $this->quienRetiraParentesco = $quienRetiraParentesco;

        return $this;
    }

    public function getQuienRetiraNombre(): ?string
    {
        return $this->quienRetiraNombre;
    }

    public function setQuienRetiraNombre(?string $quienRetiraNombre): self
    {
        $this->quienRetiraNombre = $quienRetiraNombre;

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

    public function getClinicaTieneSeguro(): ?bool
    {
        return $this->clinicaTieneSeguro;
    }

    public function setClinicaTieneSeguro(?bool $clinicaTieneSeguro): self
    {
        $this->clinicaTieneSeguro = $clinicaTieneSeguro;

        return $this;
    }

    public function getClinicaInstitucionSeguro(): ?string
    {
        return $this->clinicaInstitucionSeguro;
    }

    public function setClinicaInstitucionSeguro(?string $clinicaInstitucionSeguro): self
    {
        $this->clinicaInstitucionSeguro = $clinicaInstitucionSeguro;

        return $this;
    }

    public function getClinicaTelefonoInstitucionSeguro(): ?int
    {
        return $this->clinicaTelefonoInstitucionSeguro;
    }

    public function setClinicaTelefonoInstitucionSeguro(?int $clinicaTelefonoInstitucionSeguro): self
    {
        $this->clinicaTelefonoInstitucionSeguro = $clinicaTelefonoInstitucionSeguro;

        return $this;
    }

    public function getClinicaTieneEnfermedadCuidadoEspecial(): ?bool
    {
        return $this->clinicaTieneEnfermedadCuidadoEspecial;
    }

    public function setClinicaTieneEnfermedadCuidadoEspecial(?bool $clinicaTieneEnfermedadCuidadoEspecial): self
    {
        $this->clinicaTieneEnfermedadCuidadoEspecial = $clinicaTieneEnfermedadCuidadoEspecial;

        return $this;
    }

    public function getClinicaEnfermedadCuidadoEspecial(): ?string
    {
        return $this->clinicaEnfermedadCuidadoEspecial;
    }

    public function setClinicaEnfermedadCuidadoEspecial(?string $clinicaEnfermedadCuidadoEspecial): self
    {
        $this->clinicaEnfermedadCuidadoEspecial = $clinicaEnfermedadCuidadoEspecial;

        return $this;
    }

    public function getClinicaRecomendaciones(): ?string
    {
        return $this->clinicaRecomendaciones;
    }

    public function setClinicaRecomendaciones(?string $clinicaRecomendaciones): self
    {
        $this->clinicaRecomendaciones = $clinicaRecomendaciones;

        return $this;
    }

    public function getClinicaObservaciones(): ?string
    {
        return $this->clinicaObservaciones;
    }

    public function setClinicaObservaciones(?string $clinicaObservaciones): self
    {
        $this->clinicaObservaciones = $clinicaObservaciones;

        return $this;
    }

    public function getSolicitanteNombre(): ?string
    {
        return $this->solicitanteNombre;
    }

    public function setSolicitanteNombre(?string $solicitanteNombre): self
    {
        $this->solicitanteNombre = $solicitanteNombre;

        return $this;
    }

    public function getSolicitanteRut(): ?string
    {
        return $this->solicitanteRut;
    }

    public function setSolicitanteRut(?string $solicitanteRut): self
    {
        $this->solicitanteRut = $solicitanteRut;

        return $this;
    }

    public function getSolicitanteCorreoElectronico(): ?string
    {
        return $this->solicitanteCorreoElectronico;
    }

    public function setSolicitanteCorreoElectronico(?string $solicitanteCorreoElectronico): self
    {
        $this->solicitanteCorreoElectronico = $solicitanteCorreoElectronico;

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

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(?int $telefono): self
    {
        $this->telefono = $telefono;

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

    public function getPadreTelefono(): ?int
    {
        return $this->padreTelefono;
    }

    public function setPadreTelefono(?int $padreTelefono): self
    {
        $this->padreTelefono = $padreTelefono;

        return $this;
    }

    public function getMadreTelefono(): ?int
    {
        return $this->madreTelefono;
    }

    public function setMadreTelefono(?int $madreTelefono): self
    {
        $this->madreTelefono = $madreTelefono;

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

    public function getFechaActualizacion(): ?\DateTimeInterface
    {
        return $this->fechaActualizacion;
    }

    public function setFechaActualizacion(?\DateTimeInterface $fechaActualizacion): self
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getParentescoTelefonoContacto1(): ?Parentesco
    {
        return $this->parentescoTelefonoContacto1;
    }

    public function setParentescoTelefonoContacto1(?Parentesco $parentescoTelefonoContacto1): self
    {
        $this->parentescoTelefonoContacto1 = $parentescoTelefonoContacto1;

        return $this;
    }

    public function getParentescoTelefonoContacto2(): ?Parentesco
    {
        return $this->parentescoTelefonoContacto2;
    }

    public function setParentescoTelefonoContacto2(?Parentesco $parentescoTelefonoContacto2): self
    {
        $this->parentescoTelefonoContacto2 = $parentescoTelefonoContacto2;

        return $this;
    }

    public function getParentescoTelefonoContacto3(): ?Parentesco
    {
        return $this->parentescoTelefonoContacto3;
    }

    public function setParentescoTelefonoContacto3(?Parentesco $parentescoTelefonoContacto3): self
    {
        $this->parentescoTelefonoContacto3 = $parentescoTelefonoContacto3;

        return $this;
    }

    public function getMatriculaCompletada(): ?bool
    {
        return $this->matriculaCompletada;
    }

    public function setMatriculaCompletada(?bool $matriculaCompletada): self
    {
        $this->matriculaCompletada = $matriculaCompletada;

        return $this;
    }

    public function getMatriculaUsuario(): ?string
    {
        return $this->matriculaUsuario;
    }

    public function setMatriculaUsuario(?string $matriculaUsuario): self
    {
        $this->matriculaUsuario = $matriculaUsuario;

        return $this;
    }

    public function getMatriculaFecha(): ?\DateTimeInterface
    {
        return $this->matriculaFecha;
    }

    public function setMatriculaFecha(?\DateTimeInterface $matriculaFecha): self
    {
        $this->matriculaFecha = $matriculaFecha;

        return $this;
    }

    public function getFotoApoderado(): ?string
    {
        return $this->fotoApoderado;
    }

    public function setFotoApoderado(?string $fotoApoderado): self
    {
        $this->fotoApoderado = $fotoApoderado;

        return $this;
    }

    public function getFotoEstudiante(): ?string
    {
        return $this->fotoEstudiante;
    }

    public function setFotoEstudiante(?string $fotoEstudiante): self
    {
        $this->fotoEstudiante = $fotoEstudiante;

        return $this;
    }

    public function setFotoApoderadoFile(File $image = null)
    {
        $this->fotoApoderadoFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFotoApoderadoFile()
    {
        return $this->fotoApoderadoFile;
    }

    public function setFotoEstudianteFile(File $image = null)
    {
        $this->fotoEstudianteFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFotoEstudianteFile()
    {
        return $this->fotoEstudianteFile;
    }

    public function getNecesidadesEducativasEspecialesCual(): ?string
    {
        return $this->necesidadesEducativasEspecialesCual;
    }

    public function setNecesidadesEducativasEspecialesCual(?string $necesidadesEducativasEspecialesCual): self
    {
        $this->necesidadesEducativasEspecialesCual = $necesidadesEducativasEspecialesCual;

        return $this;
    }

    public function getPadresSeleccionReligion(): ?Religion
    {
        return $this->padresSeleccionReligion;
    }

    public function setPadresSeleccionReligion(?Religion $padresSeleccionReligion): self
    {
        $this->padresSeleccionReligion = $padresSeleccionReligion;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
   
}
