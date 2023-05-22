<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

 /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="El nombre es obligatorio")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="El apellido es obligatorio")
     */
    private $apellido;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type(type="\DateTime", message="La fecha de nacimiento debe ser un objeto DateTime")
     */
    private $fechaNacimiento;


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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTime
    {
        return $this->fechaNacimiento;
    }
    
    public function setFechaNacimiento(\DateTime $fechaNacimiento): self
    {
        $this->fechaNacimiento = $fechaNacimiento;
        return $this;
    }
}
