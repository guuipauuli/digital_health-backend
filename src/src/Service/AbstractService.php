<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\Entity;
use App\Helper\SecurityHelper;
use App\Repository\CompanyRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractService
{
    private ValidatorInterface $validatorInterface;

    private static string $entityClass;

    private ServiceEntityRepository $serviceEntityRepository;

    private SecurityHelper $security;
    private CompanyRepository $companyRepository;

    public function __construct(
        ValidatorInterface $validator,
        string $entityClass,
        EntityManagerInterface $entityManager,
        SecurityHelper $security
    )
    {
        $this->validatorInterface = $validator;
        self::$entityClass = $entityClass;
        $this->security = $security;
        $this->serviceEntityRepository = $entityManager->getRepository($entityClass);
        $this->companyRepository = $entityManager->getRepository(Company::class);
    }

    protected function deserialize(string $jsonObject, bool $validate = true): object {
        $normalizers = [
            new ObjectNormalizer(
                null, null, null, new PhpDocExtractor()
            ), new ArrayDenormalizer()
        ];

        $object = (new Serializer($normalizers, [new JsonEncoder()]))
            ->deserialize($jsonObject, self::$entityClass, 'json');
        if($validate) {
            $this->validate($object);
        }
        return $object;
    }

    protected function validate(object $object) {
        $validation = $this->validatorInterface->validate($object);
        if(count($validation)){
            $error = "";
            foreach ($validation as $e){
                $error .= $e->getMessage() . " ";
            }
            throw new ValidatorException($error);
        }
    }

    public function store(string $content): object {
        $object = $this->deserialize($content);
        if(method_exists($object, 'setCompany')) {
            $object->setCompany($this->security->getLoggedUser()->getCompany());
        }
        $this->serviceEntityRepository->add($object);
        return $object;
    }

    protected function setCompany(Entity $entity) {
        $loggedUser = $this->security->getLoggedUser();
        if(!$loggedUser->isMaster()) {
            $entity->setCompany($loggedUser->getCompany());
        }
    }
}
