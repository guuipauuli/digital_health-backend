<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'list_users', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function list(UserRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/user/{id}', name: 'list_user_by_id', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function listById(int $id, UserRepository $repository)
    {
        return $this->json($repository->findOrFail($id));
    }

    #[Route('/user', name: 'store_user', methods: self::METHOD_POST)]
    #[IsGranted('ROLE_USER')]
    public function store(UserService $service, Request $request)
    {
        return $this->json($service->storeUser($request->getContent()));
    }

    #[Route('/user/{id}', name: 'update_user', methods: self::METHOD_PUT)]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, UserService $service, Request $request)
    {
        return $this->json($service->updateUser($request->getContent(), $id));
    }

    #[Route('/user/{id}', name: 'delete_user_by_id', methods: self::METHOD_DELETE)]
    #[IsGranted('ROLE_USER')]
    public function deleteById(int $id, UserRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
