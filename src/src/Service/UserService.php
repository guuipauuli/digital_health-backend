<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends AbstractService<User>
 *
 * @method User deserialize(string $jsonObject, bool $validate = true)
 */
class UserService extends AbstractService
{
    private UserPasswordHasherInterface $hasher;
    private CompanyRepository $companyRepository;

    private UserRepository $repository;

    public function __construct(
        ValidatorInterface $validator,
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
        CompanyRepository $companyRepository
    )
    {
        parent::__construct($validator, User::class, $repository);
        $this->hasher = $hasher;
        $this->companyRepository = $companyRepository;
        $this->repository = $repository;
    }

    public function storeUser(string $content): User {
        $user = $this->mapUser($content);
        $this->repository->add($user);
        return $user;
    }

    private function mapUser(string $content): User {
        $object = json_decode($content);
        $user = $this->deserialize($content);
        if(isset($object->companyId)) {
            $user->setCompany($this->companyRepository->findOrFail($object->companyId));
        }
        if(isset($object->password)) {
            $this->hash($user, $object->password);
        }
        $this->validateFields($user);
        return $user;
    }

    private function validateFields(User $user) {
        $userWithSameEmail = $this->repository->findOneByEmail($user->getEmail());
        if($userWithSameEmail && $userWithSameEmail->getId() !== $user->getId() &&
            $userWithSameEmail->getCompany()->getId() === $user->getCompany()?->getId()) {
            throw new ValidationException('Email já cadastrado!');
        }
        if(is_null($user->getPassword())) {
            throw new ValidationException('Senha é obrigatória!');
        }
        if(is_null($user->getCompany())) {
            throw new ValidationException('Empresa é obrigatória!');
        }
    }

    private function hash(User $user, string $password) {
        $user->setPassword($this->hasher->hashPassword($user, $password));
    }
}
