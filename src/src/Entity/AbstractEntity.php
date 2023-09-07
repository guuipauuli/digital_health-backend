<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractEntity
{
    #[Assert\NotNull]
    #[ORM\Column("created_at", type: "datetime", nullable: false)]
    protected $createdAt;

    #[ORM\Column("updated_at", type: "datetime", nullable: true)]
    protected $updatedAt;

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        if(!$this->createdAt) {
            $this->createdAt = new \DateTime();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
