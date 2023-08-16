<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UserProfessional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $licenseNumber;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="UserProfessionalProfessional", mappedBy="userProfessional")
     */
    private Collection $userProfessionalProfessionals;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $authenticatorData = null;

    public function __construct()
    {
        $this->userProfessionalProfessionals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|UserProfessionalProfessional[]
     */
    public function getUserProfessionalProfessionals(): Collection
    {
        return $this->userProfessionalProfessionals;
    }

    public function addProfessional(Professional $professional): self
    {
        if (!$this->userProfessionalProfessionals->contains($professional)) {
            $userProfessionalProfessional = new UserProfessionalProfessional();
            $userProfessionalProfessional->setUserProfessional($this);
            $userProfessionalProfessional->setProfessional($professional);
            $this->userProfessionalProfessionals->add($userProfessionalProfessional);
        }

        return $this;
    }

    public function removeProfessional(Professional $professional): self
    {
        foreach ($this->userProfessionalProfessionals as $userProfessionalProfessional) {
            if ($userProfessionalProfessional->getProfessional() === $professional) {
                $this->userProfessionalProfessionals->removeElement($userProfessionalProfessional);
                break;
            }
        }

        return $this;
    }

    public function getAuthenticatorData(): ?array
    {
        return $this->authenticatorData;
    }

    public function setAuthenticatorData(?array $authenticatorData): self
    {
        $this->authenticatorData = $authenticatorData;
        return $this;
    }
}