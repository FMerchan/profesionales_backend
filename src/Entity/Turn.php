<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Turn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $duration;

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

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
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
}