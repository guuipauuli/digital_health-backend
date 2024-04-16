<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Helper\SecurityHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    private UserRepository $repository;

    public function __construct(
        ValidatorInterface $validator,
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
        SecurityHelper $security,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($validator, User::class, $entityManager, $security);
        $this->hasher = $hasher;
        $this->repository = $repository;
    }

    public function storeUser(string $content): User {
        $user = $this->mapUser($content);
        $this->repository->add($user);
        return $user;
    }

    public function updateUser(string $content, int $id): User {
        $user = $this->mapUser($content);
        $userToUpdate = $this->repository->findOrFail($id);
        $userToUpdate->setRoles($user->getRoles());
        $userToUpdate->setEmail($user->getEmail());
        $this->repository->flush();
        return $userToUpdate;
    }

    private function mapUser(string $content): User {
        $object = json_decode($content);
        $user = $this->deserialize($content);

        $this->setCompany($user);

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
