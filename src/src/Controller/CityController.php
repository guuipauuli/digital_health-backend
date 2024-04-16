<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Service\CityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class CityController extends AbstractController
{
    #[Route('/cities', name: 'list_cities', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function list(CityRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/city/{id}', name: 'list_city_by_id', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function listById(int $id, CityRepository $repository)
    {
        return $this->json($repository->findOrFail($id));
    }

//    #[Route('/city', name: 'store_city', methods: self::METHOD_POST)]
//    #[IsGranted('ROLE_USER')]
//    public function store(CityService $service, Request $request)
//    {
//        return $this->json($service->store($request->getContent()));
//    }

    #[Route('/city/{id}', name: 'update_city', methods: self::METHOD_PUT)]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, CityService $service, Request $request)
    {
        return $this->json($service->updateCity($request->getContent(), $id));
    }

    #[Route('/city/{id}', name: 'delete_city_by_id', methods: self::METHOD_DELETE)]
    #[IsGranted('ROLE_USER')]
    public function deleteById(int $id, CityRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
