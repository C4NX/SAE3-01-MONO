<?php

namespace App\Entity;

use App\Repository\VaccineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VaccineRepository::class)]
class Vaccine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNext = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCurrent = null;

    #[ORM\ManyToOne]
    private ?Animal $Animal = null;

    #[ORM\ManyToOne(inversedBy: 'vaccines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateNext(): ?\DateTimeInterface
    {
        return $this->dateNext;
    }

    public function setDateNext(?\DateTimeInterface $dateNext): self
    {
        $this->dateNext = $dateNext;

        return $this;
    }

    public function getDateCurrent(): ?\DateTimeInterface
    {
        return $this->dateCurrent;
    }

    public function setDateCurrent(?\DateTimeInterface $dateCurrent): self
    {
        $this->dateCurrent = $dateCurrent;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->Animal;
    }

    public function setAnimal(?Animal $Animal): self
    {
        $this->Animal = $Animal;

        return $this;
    }
}
