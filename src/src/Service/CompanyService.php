<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Company;
use App\Helper\SecurityHelper;
use App\Repository\AddressRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends AbstractService<Company>
 *
 * @method Company deserialize(string $jsonObject, bool $validate = true)
 */
class CompanyService extends AbstractService
{
    private CompanyRepository $repository;

    private AddressRepository $addressRepository;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        SecurityHelper $security
    )
    {
        parent::__construct($validator, Company::class, $entityManager, $security);
        $this->repository = $entityManager->getRepository(Company::class);
        $this->addressRepository = $entityManager->getRepository(Address::class);
    }

    public function storeCompany(string $content): Company {
        $company = $this->mapCompany($content);
        $this->repository->add($company);
        return $company;
    }


    public function updateCompany(string $content, int $id): Company {
        $company = $this->mapCompany($content);
        $companyToUpdate = $this->repository->findOrFail($id);
        $companyToUpdate->setAddress($company->getAddress());
        $companyToUpdate->setStateRegistration($company->getStateRegistration());
        $companyToUpdate->setFederalRegistration($company->getFederalRegistration());
        $companyToUpdate->setFantasyName($company->getFantasyName());
        $companyToUpdate->setCorporateName($company->getCorporateName());
        $this->repository->flush();
        return $companyToUpdate;
    }

    private function mapCompany(string $content): Company {
        $object = json_decode($content);
        $company = $this->deserialize($content, false);
        if(isset($object->addressId)) {
            $company->setAddress($this->addressRepository->findOrFail($object->addressId));
        }

        return $company;
    }
}
