<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\Table(name: 'city', schema: 'app')]
#[ORM\HasLifecycleCallbacks]
class City extends AbstractBasicEntity
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
    #[ORM\ManyToOne(targetEntity: State::class)]
    #[ORM\JoinColumn(nullable: false)]
    private State|null $state = null;

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

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }
}
