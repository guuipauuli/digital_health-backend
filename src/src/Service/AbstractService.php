<?php

namespace App\Service;

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

    public function __construct(ValidatorInterface $validator, string $entityClass)
    {
        $this->validatorInterface = $validator;
        self::$entityClass = $entityClass;
    }

    protected function deserialize(string $jsonObject): object {
        $normalizers = [
            new ObjectNormalizer(
                null, null, null, new PhpDocExtractor()
            ), new ArrayDenormalizer()
        ];

        $object = (new Serializer($normalizers, [new JsonEncoder()]))
            ->deserialize($jsonObject, self::$entityClass, 'json');
        $this->validate($object);
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
}