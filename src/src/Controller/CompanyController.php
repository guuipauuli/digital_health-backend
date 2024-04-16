<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'list_companies', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function list(CompanyRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/company/{id}', name: 'list_company_by_id', methods: self::METHOD_GET)]
    #[IsGranted('ROLE_USER')]
    public function listById(int $id, CompanyRepository $repository)
    {
        return $this->json($repository->findOrFail($id));
    }

    #[Route('/company', name: 'store_company', methods: self::METHOD_POST)]
    #[IsGranted('ROLE_USER')]
    public function store(CompanyService $service, Request $request)
    {
        return $this->json($service->storeCompany($request->getContent()));
    }

    #[Route('/company/{id}', name: 'update_company', methods: self::METHOD_PUT)]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, CompanyService $service, Request $request)
    {
        return $this->json($service->updateCompany($request->getContent(), $id));
    }

    #[Route('/company/{id}', name: 'delete_company_by_id', methods: self::METHOD_DELETE)]
    #[IsGranted('ROLE_USER')]
    public function deleteById(int $id, CompanyRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
