<?php

namespace App\Repository;

use App\Exception\RegistryNotFoundException;
use App\Helper\MessagesHelper;
use App\Helper\RequestDataExtractorHelper;
use App\Helper\ResponseHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function getByFilters()
    {
        $requestDataExtractor = new RequestDataExtractorHelper();
        return [
            'total' => count($this->findBy($requestDataExtractor->getFilterData())),
            'page' => ($requestDataExtractor->getPaginationData()?:0) / ($requestDataExtractor->getItemsPerPage()?:1),
            'perPage' => $requestDataExtractor->getItemsPerPage(),
            'rows' => $this->findBy(
                $requestDataExtractor->getFilterData(),
                $requestDataExtractor->getOrderData(),
                $requestDataExtractor->getItemsPerPage(),
                $requestDataExtractor->getPaginationData()
            )
        ];
    }

    public function findOrFail(int $id) {
        $entity = $this->find($id);
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
