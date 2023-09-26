<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfficeRepository")
 */
class Office
{
    const DAY_AVAILABLE_MAPPING = [
        0 => "D",
        1 => "L",
        2 => "M",
        3 => "X",
        4 => "J",
        5 => "V",
        6 => "S",
    ];

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
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $detail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(nullable=true)
     */
    private City $city;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=true)
     */
    private Country $country;

    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(nullable=true)
     */
    private State $state;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private string $postalCode;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    private string $longitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    private string $latitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private string $price;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private string $currency;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $businessDays = [];

    /**
     * @ORM\Column(type="integer")
     */
    private int $duration;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $availableTimes = [];

    /**
     * @ORM\ManyToOne(targetEntity="UserProfessional", inversedBy="offices")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserProfessional $userProfessional;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getBusinessDays(): ?array
    {
        return $this->businessDays;
    }

    public function setBusinessDays(?array $businessDays): self
    {
        $this->businessDays = $businessDays;
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

    public function getAvailableTimes(): ?array
    {
        return $this->availableTimes;
    }

    public function setAvailableTimes(?array $availableTimes): self
    {
        $this->availableTimes = $availableTimes;
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

    public function getAsArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'longitud' => $this->getLongitude(),
            'latitud' => $this->getLatitude(),
            'price' => $this->getPrice(),
        ];
    }

    public function getFullAsArray(): array
    {
        $aux = explode(" ", $this->getAddress());
        $number = $aux[count($aux) -1];
        $street = trim(str_replace($number, '', $this->getAddress()));
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'detail' => $this->getDetail(),
            'address' => $this->getAddress(),
            'longitud' => $this->getLongitude(),
            'latitud' => $this->getLatitude(),
            'price' => $this->getPrice(),
            'business_days' => $this->getBusinessDays(),
            'duration' => $this->getDuration(),
            'available_times' => $this->getAvailableTimes(),
            'localization' => [
                'address' => $street,
                'number' => $number,
                'state_id' => $this->getState()->getId(),
                'city_id' => $this->getCity()->getId(),
                'postal_code' => $this->getPostalCode(),
                'coordinates' => [
                    'longitude' => $this->getLongitude(),
                    'latitude' => $this->getLatitude(),
                ]
            ],
        ];
    }

    public function getFormattedBusinessDays(): string
    {
        $days = $this->businessDays;
        $formattedDays = implode(", ", $days);
        return $formattedDays;
    }

    public function getFormattedAvailableTimes(): string
    {
        $formattedTimes = [];

        foreach ($this->getAvailableTimes() as $time) {
            $formattedTimes[] = $time['from'] . ' - ' . $time['until'];
        }

        return implode(", ", $formattedTimes);
    }
}
