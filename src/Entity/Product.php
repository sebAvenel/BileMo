<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"list", "show"})
     * @SWG\Property(description="The unique identifier of the product.")
     * @SWG\Property(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list", "show"})
     * @SWG\Property(description="The name of the product.")
     * @SWG\Property(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({"show"})
     * @SWG\Property(description="The price of the product.")
     * @SWG\Property(type="decimal")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     * @SWG\Property(description="The firstname of the product.")
     * @SWG\Property(type="string")
     */
    private $brand;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"show"})
     * @SWG\Property(description="The description of the product.")
     * @SWG\Property(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     * @SWG\Property(description="The color of the product.")
     * @SWG\Property(type="string")
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
