<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Not blank please')]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Not blank please')]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'Products')]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Not blank please')]
    private ?string $picture = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;


    public function revision()
    {
        // public static function loadValidatorMetadata(ClassMetadata $metaData)
        // {
        //     $metaData->addPropertyConstraints('name', [
        //         new NotBlank(['message' => 'le nom de produit est obligatoire']),
        //         new Length(['min' => 3, 'max' => 255, "minMessage" => " Plus que 3 svp"])
        //     ]);

        //     $metaData->addPropertyConstraint(
        //         'price',
        //         new NotBlank(['message' => 'le prix est obligatoire']),
        //     );
        // }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpperName(): ?string
    {
        return strtoupper($this->name);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}