<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'list_companies', methods: self::METHOD_GET)]
    public function list(CompanyRepository $repository)
    {
        return $this->json($repository->getByFilters());
    }

    #[Route('/company/{id}', name: 'list_company_by_id', methods: self::METHOD_GET)]
    public function listById(int $id, CompanyRepository $repository)
    {
        return $this->json($repository->find($id));
    }

    #[Route('/company', name: 'store_company', methods: self::METHOD_POST)]
    public function store(CompanyService $service, Request $request)
    {
        return $this->json($service->store($request->getContent()));
    }

    #[Route('/company/{id}', name: 'delete_company_by_id', methods: self::METHOD_DELETE)]
    public function deleteById(int $id, CompanyRepository $repository)
    {
        return $this->json($repository->removeById($id));
    }
}
