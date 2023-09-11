<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ORM\Table(name: 'state', schema: 'app')]
#[ORM\HasLifecycleCallbacks]
class State extends AbstractBasicEntity
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    //SIGLA
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 2, unique: true, nullable: false)]
    private ?string $acronym = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): static
    {
        $this->acronym = $acronym;

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
}
