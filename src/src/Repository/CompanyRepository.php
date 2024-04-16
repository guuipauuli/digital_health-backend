<?php

namespace App\Repository;

use App\Entity\Company;
use App\Helper\SecurityHelper;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneByCriteriaAndCompany(array $criteria, array $orderBy = null)
 * @method Company      findOrFail(int $id)
 * @method Company[]    findAll()
 * @method Company[]    findByCriteriaAndCompany(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry, SecurityHelper $security)
    {
        parent::__construct($registry, Company::class, $security);
    }
}
