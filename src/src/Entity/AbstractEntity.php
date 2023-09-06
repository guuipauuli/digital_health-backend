<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    private ?int $id = null;

    #[ORM\Column("created_at", type: "datetime", nullable: false, options: ["default" => new \DateTime()])]
    protected $createdAt;

    #[ORM\Column("updated_at", type: "datetime", nullable: true)]
    protected $updatedAt;

//    abstract public function setId(int $id);

//    abstract public function getId();

//    public function getId(): ?int
//    {
//        return $this->id;
//    }
}
