<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $race = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 50)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?bool $isDomestic = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'animals')]
    private ?CategoryAnimal $CategoryAnimal = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?Client $ClientAnimal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPath = null;

    #[ORM\OneToMany(mappedBy: 'Animal', targetEntity: AnimalRecord::class, cascade: ['persist', 'remove'])]
    private Collection $animalRecords;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Appointment::class, cascade: ['remove'])]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Vaccine::class)]
    private Collection $vaccines;

    public function __construct()
    {
        $this->animalRecords = new ArrayCollection();
        $this->appointments = new ArrayCollection();
        $this->vaccines = new ArrayCollection();
    }

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function isIsDomestic(): ?bool
    {
        return $this->isDomestic;
    }

    public function setIsDomestic(bool $isDomestic): self
    {
        $this->isDomestic = $isDomestic;

        return $this;
    }

    public function getCategoryAnimal(): ?CategoryAnimal
    {
        return $this->CategoryAnimal;
    }

    public function setCategoryAnimal(?CategoryAnimal $CategoryAnimal): self
    {
        $this->CategoryAnimal = $CategoryAnimal;

        return $this;
    }

    public function getClientAnimal(): ?Client
    {
        return $this->ClientAnimal;
    }

    public function setClientAnimal(?Client $ClientAnimal): self
    {
        $this->ClientAnimal = $ClientAnimal;

        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    public function getDisplayName(): string
    {
        $category = $this->CategoryAnimal?->getName();
        return "$this->name ($category)";
    }

    /**
     * @return Collection<int, AnimalRecord>
     */
    public function getAnimalRecords(): Collection
    {
        return $this->animalRecords;
    }

    public function addAnimalRecord(AnimalRecord $animalRecord): self
    {
        if (!$this->animalRecords->contains($animalRecord)) {
            $this->animalRecords->add($animalRecord);
            $animalRecord->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalRecord(AnimalRecord $animalRecord): self
    {
        if ($this->animalRecords->removeElement($animalRecord)) {
            // set the owning side to null (unless already changed)
            if ($animalRecord->getAnimal() === $this) {
                $animalRecord->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setAnimal($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getAnimal() === $this) {
                $appointment->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vaccine>
     */
    public function getVaccines(): Collection
    {
        return $this->vaccines;
    }

    public function addVaccine(Vaccine $vaccine): self
    {
        if (!$this->vaccines->contains($vaccine)) {
            $this->vaccines->add($vaccine);
            $vaccine->setAnimal($this);
        }

        return $this;
    }

    public function removeVaccine(Vaccine $vaccine): self
    {
        if ($this->vaccines->removeElement($vaccine)) {
            // set the owning side to null (unless already changed)
            if ($vaccine->getAnimal() === $this) {
                $vaccine->setAnimal(null);
            }
        }

        return $this;
    }
}
