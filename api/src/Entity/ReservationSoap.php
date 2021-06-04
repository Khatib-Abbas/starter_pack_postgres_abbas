<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: [
        'groups' => ['external-resource_read']
    ]
)]

class ReservationSoap{


    #[ApiProperty(identifier: true)]
    #[Groups(['external-resource_read'])]
    private string $ulbId;
    #[Groups(['external-resource_read'])]
    private string $startAt;
    #[Groups(['external-resource_read'])]
    private string $endAt;
    #[Groups(['external-resource_read'])]
    private string $department;
    #[Groups(['external-resource_read'])]
    private string $reservationType;
    #[Groups(['external-resource_read'])]
    private string $location;
    public function __construct(

        string $ulbId ,
        string $startAt,
        string $endAt,
        string $department,
        string $reservationType,
        string $location
    )
    {
        $this->ulbId = $ulbId;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->department = $department;
        $this->reservationType = $reservationType;
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getUlbId(): string
    {
        return  $this->ulbId;
    }

    /**
     * @param string $ulbId
     */
    public function setUlbId(string $ulbId): void
    {
        $this->ulbId = $ulbId;
    }
    /**
     * @return array
     */
    public function getImage(): array
    {
        return $this->image;
    }

    /**
     * @param array $image
     */
    public function setImage(array $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getStartAt(): string
    {
        return $this->startAt;
    }

    /**
     * @param string $startAt
     */
    public function setStartAt(string $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return string
     */
    public function getEndAt(): string
    {
        return $this->endAt;
    }

    /**
     * @param string $endAt
     */
    public function setEndAt(string $endAt): void
    {
        $this->endAt = $endAt;
    }

    /**
     * @return string
     */
    public function getDepartment(): string
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment(string $department): void
    {
        $this->department = $department;
    }

    /**
     * @return string
     */
    public function getReservationType(): string
    {
        return $this->reservationType;
    }

    /**
     * @param string $reservationType
     */
    public function setReservationType(string $reservationType): void
    {
        $this->reservationType = $reservationType;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }



}
