<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity extends AbstractBasicEntity
{
    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected Company|null $company = null;

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
