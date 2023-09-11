<?php

namespace App\Repository;

use App\Entity\Address;
use App\Helper\SecurityHelper;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneByCriteriaAndCompany(array $criteria, array $orderBy = null)
 * @method Address      findOrFail(int $id)
 * @method Address[]    findAll()
 * @method Address[]    findByCriteriaAndCompany(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry, SecurityHelper $security)
    {
        parent::__construct($registry, Address::class, $security);
    }
}
