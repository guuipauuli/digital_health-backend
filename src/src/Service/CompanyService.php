<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyService extends AbstractService
{
    public function __construct(ValidatorInterface $validator, CompanyRepository $repository)
    {
        parent::__construct($validator, Company::class, $repository);
    }
}
