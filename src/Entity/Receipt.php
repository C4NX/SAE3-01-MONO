<?php

namespace App\Entity;

use App\Repository\ReceiptRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceiptRepository::class)]
class Receipt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $totalCost = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $receiptAt = null;

    #[ORM\Column]
    private ?float $VAT = null;

    #[ORM\OneToOne(mappedBy: 'receipt', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalCost(): ?float
    {
        return $this->totalCost;
    }

    public function setTotalCost(float $totalCost): self
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getReceiptAt(): ?\DateTimeInterface
    {
        return $this->receiptAt;
    }

    public function setReceiptAt(\DateTimeInterface $receiptAt): self
    {
        $this->receiptAt = $receiptAt;

        return $this;
    }

    public function getVAT(): ?float
    {
        return $this->VAT;
    }

    public function setVAT(float $VAT): self
    {
        $this->VAT = $VAT;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): self
    {
        // unset the owning side of the relation if necessary
        if (null === $appointment && null !== $this->appointment) {
            $this->appointment->setReceipt(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $appointment && $appointment->getReceipt() !== $this) {
            $appointment->setReceipt($this);
        }

        $this->appointment = $appointment;

        return $this;
    }
}
