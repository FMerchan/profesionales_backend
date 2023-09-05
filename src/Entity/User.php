<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="json")
     */
    private ?array $roles = [];

    /**
     * @ORM\Column(type="string", length=250, unique=true)
     */
    private ?string $password = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isEnable = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isBlocked = false;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Country $country;

    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?State $state;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?City $city;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $postalCode = null;

    /**
     * @ORM\OneToOne(targetEntity="UserProfessional", mappedBy="user")
     */
    private ?UserProfessional $userProfessional = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Añadir el rol por defecto si no está presente
        return array_unique($roles);
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;


        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function addRole($role): void
    {
        if (is_null($this->roles) || !in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole($role): void
    {
        $key = array_search($role, $this->roles, true);
        if ($key !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getSalt()
    {
        return "";
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getIsEnable(): bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;
        return $this;
    }

    public function getIsBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getUserProfessional(): ?UserProfessional
    {
        return $this->userProfessional;
    }
}
