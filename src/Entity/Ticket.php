<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ticket name is required')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Ticket name cannot be longer than {{ limit }} characters'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'Price is required')]
    #[Assert\Positive(message: 'Price must be a positive number')]
    private ?float $price = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: 'Currency is required')]
    private ?string $currency = null;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $amount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $sold = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Orm\ManyToOne(targetEntity: Event::class, inversedBy: 'tickets')]
    #[Orm\JoinColumn(nullable: false)]
    private ?Event $event = null;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): static {
        $this->price = $price;
        return $this;
    }

    public function getAmount(): ?int {
        return $this->amount;
    }

    public function setAmount(int $amount): static {
        $this->amount = $amount;
        return $this;
    }

    public function getSold(): ?int {
        return $this->sold;
    }

    public function setSold(int $sold): static {
        $this->sold = $sold;
        return $this;
    }

    public function getCurrency(): ?string {
        return $this->currency;
    }

    public function setCurrency(string $currency): static {
        $this->currency = $currency;
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

    public function getEvent(): ?Event {
        return $this->event;
    }

    public function setEvent(?Event $event): static {
        $this->event = $event;
        return $this;
    }
}
