<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends AbstractService
{
    private UserRepository $repository;

    private UserPasswordHasherInterface $hasher;

    public function __construct(ValidatorInterface $validator, UserRepository $repository, UserPasswordHasherInterface $hasher)
    {
        parent::__construct($validator, User::class);
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    public function store(object $object): User {
        $user = $this->deserialize(json_encode($object));
        $this->hash($user, $object->password);
        $this->repository->add($user, true);
        return $user;
    }

    private function hash(User $user, string $password) {
        $user->setPassword($this->hasher->hashPassword($user, $password));
    }
}
