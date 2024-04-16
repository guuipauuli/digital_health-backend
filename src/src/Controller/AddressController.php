<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\Service\AddressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class AddressController extends AbstractController
{
    #[Route('/addresses', name: 'list_addresses', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function list(AddressRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/address/{id}', name: 'list_address_by_id', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function listById(int $id, AddressRepository $repository)
    {
        return $this->json($repository->findOrFail($id));
    }

    #[Route('/address', name: 'store_address', methods: self::METHOD_POST)]
    #[IsGranted('ROLE_USER')]
    public function store(AddressService $service, Request $request)
    {
        return $this->json($service->storeAddress($request->getContent()));
    }

    #[Route('/address/{id}', name: 'update_address', methods: self::METHOD_PUT)]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, AddressService $service, Request $request)
    {
        return $this->json($service->updateAddress($request->getContent(), $id));
    }

    #[Route('/address/{id}', name: 'delete_address_by_id', methods: self::METHOD_DELETE)]
    #[IsGranted('ROLE_USER')]
    public function deleteById(int $id, AddressRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
