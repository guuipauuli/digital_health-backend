<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Neighborhood;
use App\Helper\SecurityHelper;
use App\Repository\AddressRepository;
use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends AbstractService<Address>
 *
 * @method Address deserialize(string $jsonObject, bool $validate = true)
 */
class AddressService extends AbstractService
{
    private AddressRepository $repository;

    private NeighborhoodRepository $neighborhoodRepository;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SecurityHelper $security)
    {
        parent::__construct($validator, Address::class, $entityManager, $security);
        $this->repository = $entityManager->getRepository(Address::class);
        $this->neighborhoodRepository = $entityManager->getRepository(Neighborhood::class);
    }

    public function storeAddress(string $content): Address {
        $address = $this->mapAddress($content);
        $this->repository->add($address);
        return $address;
    }

    public function updateAddress(string $content, int $id): Address {
        $address = $this->mapAddress($content);
        $addressToUpdate = $this->repository->findOrFail($id);
        $addressToUpdate->setStreet($address->getStreet());
        $addressToUpdate->setNeighborhood($address->getNeighborhood());
        $addressToUpdate->setPostalCode($address->getPostalCode());
        $this->repository->flush();
        return $addressToUpdate;
    }

    private function mapAddress(string $content): Address {
        $object = json_decode($content);
        $address = $this->deserialize($content, false);
        if(isset($object->neighborhoodId)) {
            $address->setNeighborhood($this->neighborhoodRepository->findOrFail($object->neighborhoodId));
        }
        $this->setCompany($address);

        return $address;
    }
}
