<?php

namespace App\Entity;

use App\Repository\PurchaseTicketRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseTicketRepository::class)]
class PurchaseTicket {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\Positive(message: 'Quantity must be a positive number')]
    #[Assert\NotBlank(message: 'Quantity is required')]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'Quantity must be between {{ min }} and {{ max }}')]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Ticket::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticket = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getQuantity(): ?int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static {
        $this->quantity = $quantity;
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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static {
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

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): static {
        $this->user = $user;
        return $this;
    }
}
