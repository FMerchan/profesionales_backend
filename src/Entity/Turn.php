<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TurnRepository")
 */
class Turn
{
    const CANCELED_BY_USER = 1;
    const CANCELED_BY_PROFESSIONAL = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $professionalObservation = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $userObservation = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $cancelled = false;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $cancelledBy = null;

    /**
     * @ORM\ManyToOne(targetEntity="UserProfessional")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserProfessional $userProfessional;

    /**
     * @ORM\ManyToOne(targetEntity="Office")
     * @ORM\JoinColumn(nullable=false)
     */
    private Office $office;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getUserProfessional(): UserProfessional
    {
        return $this->userProfessional;
    }

    public function setUserProfessional(UserProfessional $userProfessional): self
    {
        $this->userProfessional = $userProfessional;
        return $this;
    }

    public function getOffice(): Office
    {
        return $this->office;
    }

    public function setOffice(Office $office): self
    {
        $this->office = $office;
        return $this;
    }

    public function getProfessionalObservation(): ?string
    {
        return $this->professionalObservation;
    }

    public function setProfessionalObservation(?string $professionalObservation): self
    {
        $this->professionalObservation = $professionalObservation;
        return $this;
    }

    public function getUserObservation(): ?string
    {
        return $this->userObservation;
    }

    public function setUserObservation(?string $userObservation): self
    {
        $this->userObservation = $userObservation;
        return $this;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): self
    {
        $this->cancelled = $cancelled;
        return $this;
    }

    public function getCancelledBy(): ?int
    {
        return $this->cancelledBy;
    }

    public function setCancelledBy(?int $cancelledBy): self
    {
        $this->cancelledBy = $cancelledBy;
        return $this;
    }

    public function getAsArray(): array
    {
        $office = $this->getOffice();
        $professional = $this->office->getUserProfessional();
        return [
            'id' => $this->getId(),
            'date' => $this->getDate()->format('Y-m-d H:i'),
            'duration' => $this->getDuration(),
            'office' => $office->getAsArray(),
            'professional' => $professional->getUserProfessionalAsArray(),
            'user' => $this->getUserProfessional()->getUserProfessionalAsArray()
        ];
    }

}