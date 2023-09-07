<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }
}
