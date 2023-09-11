<?php

namespace App\Repository;

use App\Entity\Neighborhood;
use App\Helper\SecurityHelper;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Neighborhood>
 *
 * @method Neighborhood|null find($id, $lockMode = null, $lockVersion = null)
 * @method Neighborhood|null findOneByCriteriaAndCompany(array $criteria, array $orderBy = null)
 * @method Neighborhood      findOrFail(int $id)
 * @method Neighborhood[]    findAll()
 * @method Neighborhood[]    findByCriteriaAndCompany(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NeighborhoodRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry, SecurityHelper $security)
    {
        parent::__construct($registry, Neighborhood::class, $security);
    }
}
