<?php

namespace App\Entity;

use App\Repository\VetoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VetoRepository::class)]
class Veto extends User
{
    #[ORM\OneToMany(mappedBy: 'veto', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToOne(inversedBy: 'veto', cascade: ['persist', 'remove'])]
    private ?Agenda $agenda = null;

    public function __construct()
    {
        parent::__construct();
        $this->appointments = new ArrayCollection();
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
            $appointment->setVeto($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getVeto() === $this) {
                $appointment->setVeto(null);
            }
        }

        return $this;
    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function getDisplayName(): string
    {
        return sprintf("Dr %s $this->firstName", strtoupper($this->lastName));
    }
}
