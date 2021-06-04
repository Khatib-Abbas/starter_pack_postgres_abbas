<?php
# la référence api platform : https://api-platform.com/
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
#[ApiResource(
    collectionOperations: ['get','post'],
    itemOperations: ['get','put','delete'],
    denormalizationContext: ['groups' => ['product_write']],
    normalizationContext: ['groups' => ['product_read']]
)]
# En savoir plus sur les filters : https://api-platform.com/docs/core/filters/#filters
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact','name'=>'exact','type.name'=>'exact'])]

#comprendre les voter : https://api-platform.com/docs/core/security/#hooking-custom-permission-checks-using-voters
# créer une classe en CLI : php bin/console make:entity
# créer une migration : php bin/console make:migration
# créer une migrer vers la db : php bin/console make:migration
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['product_write','product_read'])]
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['product_write','product_read'])]
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="products")
     */
    #[Groups(['product_write','product_read'])]
    private ?Type $type;

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }
}
