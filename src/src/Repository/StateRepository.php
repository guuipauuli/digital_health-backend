<?php

namespace App\Repository;

use App\Entity\State;
use App\Helper\SecurityHelper;
use Doctrine\Persistence\ManagerRegistry;

class StateRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry, SecurityHelper $security)
    {
        parent::__construct($registry, State::class, $security);
    }
}
