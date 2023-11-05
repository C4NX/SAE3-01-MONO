<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    #[ORM\Column]
    private ?bool $isAnHusbandry = null;

    #[ORM\OneToMany(mappedBy: 'ClientAnimal', targetEntity: Animal::class, cascade: ['remove'])]
    private Collection $animals;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Address::class)]
    private Collection $adresses;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Appointment::class)]
    private Collection $appointments;

    public function __construct()
    {
        parent::__construct();
        $this->animals = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function isIsAnHusbandry(): ?bool
    {
        return $this->isAnHusbandry;
    }

    public function setIsAnHusbandry(bool $isAnHusbandry): self
    {
        $this->isAnHusbandry = $isAnHusbandry;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setClientAnimal($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getClientAnimal() === $this) {
                $animal->setClientAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Address $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setClient($this);
        }

        return $this;
    }

    public function removeAdress(Address $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getClient() === $this) {
                $adress->setClient(null);
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
            $appointment->setClient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getClient() === $this) {
                $appointment->setClient(null);
            }
        }

        return $this;
    }
}
