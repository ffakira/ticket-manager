<?php

namespace App\Entity;

use App\Form\Validator\EventEndDateConstraint;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[EventEndDateConstraint]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Event name is required')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Event name cannot be longer than {{ limit }} characters'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description is required')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Start date is required')]
    // #[Assert\Date(message: 'End date must be a valid date')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'End date is required')]
    // #[Assert\Date(message: 'End date must be a valid date')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Start time is required')]
    // #[Assert\Time(message: 'Start time must be a valid time', withSeconds: false)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: 'End time is required')]
    // #[Assert\Time(message: 'End time must be a valid time', withSeconds: false)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Location is required')]
    private ?string $location = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[Orm\OneToMany(mappedBy: 'event', targetEntity: Ticket::class, cascade: ['persist', 'remove'])]
    private Collection $tickets;

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

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): static {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static {
        $this->endDate = $endDate;
        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static {
        $this->startTime = $startTime;
        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static {
        $this->endTime = $endTime;
        return $this;
    }

    public function getLocation(): ?string {
        return $this->location;
    }

    public function setLocation(string $location): static {
        $this->location = $location;
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

    public function getTickets(): Collection {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static {
        if ($this->tickets->removeElement($ticket)) {
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }
        return $this;
    }
}
