<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
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
