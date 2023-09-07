<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/users', name: 'list_users', methods: self::METHOD_GET)]
    public function list(UserRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/user/{id}', name: 'list_user_by_id', methods: self::METHOD_GET)]
    public function listById(int $id, UserRepository $repository)
    {
        return $this->json($repository->find($id));
    }

    #[Route('/user', name: 'store_user', methods: self::METHOD_POST)]
    public function store(UserService $service, Request $request)
    {
        return $this->json($service->store(json_decode($request->getContent())));
    }

    #[Route('/user/{id}', name: 'delete_user_by_id', methods: self::METHOD_DELETE)]
    public function deleteById(int $id, UserRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
