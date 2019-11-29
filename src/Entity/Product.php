<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups = {"list", "show"})
 * )
 *
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "app_product_index",
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups = {"show"})
 * )
 *
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @SWG\Property(description="The unique identifier of the product.")
     * @SWG\Property(type="integer")
     * @Serializer\Groups({"list", "show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="The name of the product.")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"list", "show"})
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @SWG\Property(description="The price of the product.")
     * @SWG\Property(type="decimal")
     * @Serializer\Groups({"show"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="The firstname of the product.")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"show"})
     */
    private $brand;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(description="The description of the product.")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"show"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="The color of the product.")
     * @SWG\Property(type="string")
     * @Serializer\Groups({"show"})
     */
    private $color;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
