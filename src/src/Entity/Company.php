<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\Table(name: 'company', schema: 'app')]
#[ORM\HasLifecycleCallbacks]
class Company extends AbstractBasicEntity
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180)]
    private ?string $corporateName = null;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 180)]
    private ?string $fantasyName = null;

    //CNPJ
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 14, unique: true)]
    private ?string $federalRegistration = null;

    //IE
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[ORM\Column(length: 9, unique: true)]
    private ?string $stateRegistration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCorporateName(): ?string
    {
        return $this->corporateName;
    }

    public function setCorporateName(?string $corporateName): static
    {
        $this->corporateName = $corporateName;

        return $this;
    }

    public function getFantasyName(): ?string
    {
        return $this->fantasyName;
    }

    public function setFantasyName(?string $fantasyName): static
    {
        $this->fantasyName = $fantasyName;

        return $this;
    }

    public function getFederalRegistration(): ?string
    {
        return $this->federalRegistration;
    }

    public function setFederalRegistration(?string $federalRegistration): static
    {
        $this->federalRegistration = $federalRegistration;

        return $this;
    }

    public function getStateRegistration(): ?string
    {
        return $this->stateRegistration;
    }

    public function setStateRegistration(?string $stateRegistration): static
    {
        $this->stateRegistration = $stateRegistration;

        return $this;
    }
}
