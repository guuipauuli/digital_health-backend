<?php

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function __construct(ValidatorInterface $validator, string $entityClass, ServiceEntityRepository $repository)
    {
        $this->validatorInterface = $validator;
        $this->serviceEntityRepository = $repository;
        self::$entityClass = $entityClass;
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
        $this->serviceEntityRepository->add($object);
        return $object;
    }
}
