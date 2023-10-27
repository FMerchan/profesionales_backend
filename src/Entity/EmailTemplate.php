<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailTemplateRepository")
 */
class EmailTemplate
{
    const TEMPLATE_APPOINTMENT_SCHEDULED_PERSONAL = 'TEMPLATE_APPOINTMENT_SCHEDULED_PERSONAL';
    const TEMPLATE_APPOINTMENT_CANCELED_PERSONAL = 'TEMPLATE_APPOINTMENT_CANCELED_PERSONAL';
    const TEMPLATE_DAILY_APPOINTMENTS_REMINDER_PERSONAL = 'TEMPLATE_DAILY_APPOINTMENTS_REMINDER_PERSONAL';

    const TEMPLATE_APPOINTMENT_SCHEDULED_PROFESSIONAL = 'TEMPLATE_APPOINTMENT_SCHEDULED_PROFESSIONAL';
    const TEMPLATE_APPOINTMENT_CANCELED_PROFESSIONAL = 'TEMPLATE_APPOINTMENT_CANCELED_PROFESSIONAL';
    const TEMPLATE_DAILY_APPOINTMENTS_REMINDER_PROFESSIONAL = 'TEMPLATE_DAILY_APPOINTMENTS_REMINDER_PROFESSIONAL';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
