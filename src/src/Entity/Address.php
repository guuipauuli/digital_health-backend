<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'address', schema: 'app')]
#[ORM\HasLifecycleCallbacks]
class Address extends AbstractEntity implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180, nullable: false)]
    private ?string $street = null;

    #[Assert\Type('string')]
    #[ORM\Column(length: 8, nullable: true)]
    private ?string $postalCode = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Neighborhood::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Neighborhood|null $neighborhood = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getNeighborhood(): ?Neighborhood
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?Neighborhood $neighborhood): static
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "street" => $this->getStreet(),
            "postalCode" => $this->getPostalCode(),
            "neighborhood" => $this->getNeighborhood()
        ];
    }
}
