<?php

namespace App\Repository;

use App\Entity\City;
use App\Helper\SecurityHelper;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneByCriteriaAndCompany(array $criteria, array $orderBy = null)
 * @method City      findOrFail(int $id)
 * @method City[]    findAll()
 * @method City[]    findByCriteriaAndCompany(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry, SecurityHelper $security)
    {
        parent::__construct($registry, City::class, $security);
    }
}
