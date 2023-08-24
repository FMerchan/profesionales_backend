<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UserProfessionalProfessional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserProfessional")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserProfessional $userProfessional;

    /**
     * @ORM\ManyToOne(targetEntity="Professional")
     * @ORM\JoinColumn(nullable=false)
     */
    private Professional $professional;

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString() {
        return $this->professional->getName();
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

    public function getProfessional(): Professional
    {
        return $this->professional;
    }

    public function setProfessional(Professional $professional): self
    {
        $this->professional = $professional;

        return $this;
    }
}