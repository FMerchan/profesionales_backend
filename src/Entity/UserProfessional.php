<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfessionalRepository")
 */
class UserProfessional
{
    CONST TYPE_USER = 1;
    CONST TYPE_PROFESSIONAL = 2;

    CONST TYPES = [
        self::TYPE_USER,
        self::TYPE_PROFESSIONAL
    ];

    const CONTACT_WHATS_APP = 1;
    const CONTACT_MESSAGE = 2;
    const CONTACT_EMAIL = 3;

    CONST CONTACTS_OPTIONS = [
        self::CONTACT_WHATS_APP,
        self::CONTACT_MESSAGE,
        self::CONTACT_EMAIL,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $licenseNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private int $contactBy = self::CONTACT_WHATS_APP;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="UserProfessionalProfessional", mappedBy="userProfessional", cascade={"persist"})
     */
    private Collection $userProfessionalProfessionals;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $authenticatorData = null;

    /**
     * @ORM\OneToMany(targetEntity="Office", mappedBy="userProfessional")
     */
    private $offices;

    public function __construct()
    {
        $this->userProfessionalProfessionals = new ArrayCollection();
        $this->offices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): ?string
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

    public function getContactBy(): string
    {
        return $this->contactBy;
    }

    public function setContactBy(string $contactBy): self
    {
        $this->contactBy = $contactBy;

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException('Invalid type value');
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection|Office[]
     */
    public function getOffices(): Collection
    {
        return $this->offices;
    }

    public function getProfessionsNames(): string
    {
        $professionNames = [];
        foreach ($this->userProfessionalProfessionals as $userProfessionalProfessional) {
            $profession = $userProfessionalProfessional->getProfessional();
            if ($profession) {
                $professionNames[] = $profession->getName();
            }
        }

        return implode(', ', $professionNames);
    }

    public function getFullName(): string {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getUserProfessionalAsArray(): array
    {
        $user = $this->getUser();
        return [
            'id'            => $this->getId(),
            'fullName'      => $this->getFullName(),
            'name'          => $this->getFirstName(),
            'lastName'      => $this->getLastName(),
            'phoneNumber'   => $this->getPhoneNumber(),
            'email'         => $user->getEmail(),
            'licenseNumber' => $this->getLicenseNumber(),
            'offices'       => $this->getOfficesAsArray(),
        ];
    }

    public function getOfficesAsArray(): array
    {
        $officesData = [];
        foreach ($this->getOffices() as $office) {
            $officesData[] = $office->getAsArray();
        }
        return $officesData;
    }
}