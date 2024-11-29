<?php

namespace App\Entity;

use App\Repository\OrderSummaryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderSummaryRepository::class)]
class OrderSummary {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(length: 10)]
    private ?string $currency = null;

    #[ORM\Column]
    private ?float $discount = null;

    #[ORM\Column]
    private ?float $serviceFee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Orm\ManyToOne(targetEntity: Ticket::class)]
    #[Orm\JoinColumn(nullable: false)]
    private ?Ticket $ticket = null;

    #[Orm\ManyToOne(targetEntity: PurchaseTicket::class)]
    #[Orm\JoinColumn(nullable: false)]
    private ?PurchaseTicket $purchaseTicket = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTotalAmount(): ?float {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getCurrency(): ?string {
        return $this->currency;
    }

    public function setCurrency(string $currency): static {
        $this->currency = $currency;
        return $this;
    }

    public function getDiscount(): ?float {
        return $this->discount;
    }

    public function setDiscount(float $discount): static {
        $this->discount = $discount;
        return $this;
    }

    public function getServiceFee(): ?float {
        return $this->serviceFee;
    }

    public function setServiceFee(float $serviceFee): static {
        $this->serviceFee = $serviceFee;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getTicket(): ?Ticket {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): static {
        $this->ticket = $ticket;
        return $this;
    }

    public function getPurchaseTicket(): ?PurchaseTicket {
        return $this->purchaseTicket;
    }

    public function setPurchaseTicket(?PurchaseTicket $purchaseTicket): static {
        $this->purchaseTicket = $purchaseTicket;
        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): static {
        $this->user = $user;
        return $this;
    }
}
