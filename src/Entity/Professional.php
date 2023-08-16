<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfessionalRepository")
 */
class Professional
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
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $hasSpecification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHasSpecification(): ?bool
    {
        return $this->hasSpecification;
    }

    public function setHasSpecification(bool $hasSpecification): self
    {
        $this->hasSpecification = $hasSpecification;

        return $this;
    }
}
