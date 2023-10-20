<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LegalDocumentRepository")
 */
class LegalDocument
{
    const TERMS_AND_CONDITIONS = 1;
    const PRIVACY_POLICY = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank
     */
    private ?int $type;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $validFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $validUntil;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTimeInterface $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeInterface $validUntil): void
    {
        $this->validUntil = $validUntil;
    }
}