<?php

namespace App\Repository;

use App\Entity\Company;
use App\Exception\RegistryNotFoundException;
use App\Helper\MessagesHelper;
use App\Helper\RequestDataExtractorHelper;
use App\Helper\ResponseHelper;
use App\Helper\SecurityHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRepository extends ServiceEntityRepository
{
    private SecurityHelper $security;

    public function __construct(ManagerRegistry $registry, string $entityClass, SecurityHelper $security)
    {
        parent::__construct($registry, $entityClass);
        $this->security = $security;
    }

    public function getByFilters()
    {
        $requestDataExtractor = new RequestDataExtractorHelper();
        return [
            'total' => count($this->findByCriteriaAndCompany($requestDataExtractor->getFilterData())),
            'page' => ($requestDataExtractor->getPaginationData()?:0) / ($requestDataExtractor->getItemsPerPage()?:1),
            'perPage' => $requestDataExtractor->getItemsPerPage(),
            'rows' => $this->findByCriteriaAndCompany(
                $requestDataExtractor->getFilterData(),
                $requestDataExtractor->getOrderData(),
                $requestDataExtractor->getItemsPerPage(),
                $requestDataExtractor->getPaginationData()
            )
        ];
    }

    public function findOrFail(int $id) {
        $entity = $this->findOneByCriteriaAndCompany(array_merge(['id' => $id], $this->getCriteria()));
        if(is_null($entity)) {
            throw new RegistryNotFoundException(MessagesHelper::NOT_FOUND);
        }
        return $entity;
    }

    public function add(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }

    public function remove(?object $entity, bool $flush = true): void
    {
        if(is_null($entity)){
            throw new RegistryNotFoundException(
                'Registro não encontrado para os filtros informados', Response::HTTP_NOT_FOUND);
        }
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->flush();
        }
    }

    public function removeById(int $id) {
        $this->remove($this->findOrFail($id));
        return new ResponseHelper(Response::HTTP_OK, MessagesHelper::SUCCESSFULLY_DELETED);
    }

    public function findOneByCriteriaAndCompany(array $criteria, ?array $orderBy = null)
    {
        return $this->findOneBy(array_merge($criteria, $this->getCriteria()), $orderBy);
    }

    public function findByCriteriaAndCompany(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findBy(array_merge($criteria, $this->getCriteria()), $orderBy, $limit, $offset);
    }

    private function getCriteria(){
        $isCompany = $this->getClassMetadata()->name === Company::class;
        if(!method_exists($this->getClassMetadata()->name, 'getCompany') && !$isCompany) {
            return [];
        }

        $user = $this->security->getLoggedUser();
        if(is_null($user)) {
            return [$isCompany ? 'id' : 'company' => 0];
        }
        if($user->isMaster()) {
            return [];
        }
        return [$isCompany ? 'id' : 'company' => $user->getCompany()?->getId()];
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }

    protected function getTableName()
    {
        return $this->getClassMetadata()->getTableName();
    }

    protected function getSchemaName()
    {
        return $this->getClassMetadata()->getSchemaName();
    }
}
