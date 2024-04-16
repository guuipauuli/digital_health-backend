<?php

namespace App\Entity;

use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: NeighborhoodRepository::class)]
#[ORM\Table(name: 'neighborhood', schema: 'app')]
#[ORM\HasLifecycleCallbacks]
class Neighborhood extends AbstractBasicEntity
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180, nullable: false)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: City::class)]
    #[ORM\JoinColumn(nullable: false)]
    private City|null $city = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }
}
