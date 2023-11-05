<?php

namespace App\Entity;

use App\Repository\TypeAppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeAppointmentRepository::class)]
class TypeAppointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libTypeApp = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibTypeApp(): ?string
    {
        return $this->libTypeApp;
    }

    public function setLibTypeApp(string $libTypeApp): self
    {
        $this->libTypeApp = $libTypeApp;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
