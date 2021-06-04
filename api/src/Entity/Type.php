<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
#[ApiResource(
    #En savoir plus sur les collectionOperations && itemOperations  https://api-platform.com/docs/core/operations/#operations
    collectionOperations: ['get','post'],
    itemOperations: ['get','put','delete'],
    #En savoir plus sur les denormalizationContext && normalizationContext https://api-platform.com/docs/core/serialization/#the-serialization-context-groups-and-relations
    denormalizationContext: ['groups' => ['type_write']],
    normalizationContext: ['groups' => ['type_read']]
)]
# En savoir plus sur les filters : https://api-platform.com/docs/core/filters/#filters
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact','name'=>'exact'])]
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['type_write','type_read','product_read'])]
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['type_write','type_read','product_read'])]
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="type")
     */
    #[Groups(['type_read'])]
    private  $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getType() === $this) {
                $product->setType(null);
            }
        }

        return $this;
    }
}
