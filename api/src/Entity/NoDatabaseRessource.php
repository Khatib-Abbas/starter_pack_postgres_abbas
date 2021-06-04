<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    #more info https://api-platform.com/docs/core/serialization/#the-serialization-context-groups-and-relations
    shortName: 'external-resource',
    normalizationContext: [
        'groups' => ['no-database-external-resource_read']
    ],
    paginationItemsPerPage: 7

)]
class NoDatabaseRessource{

    #[ApiProperty(identifier: true)]
    #[Groups(['no-database-external-resource_read'])]
    private string $ulbId;
    #[Groups(['no-database-external-resource_read'])]
    private array $image;


    public function __construct(
        string $ulbId ,
        array $image,

    )
    {
        $this->ulbId = $ulbId;
        $this->image = $image;

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

}
