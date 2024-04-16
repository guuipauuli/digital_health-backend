<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\State;
use App\Helper\SecurityHelper;
use App\Repository\CityRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @extends AbstractService<City>
 *
 * @method City deserialize(string $jsonObject, bool $validate = true)
 */
class CityService extends AbstractService
{
    private CityRepository $repository;

    private StateRepository $stateRepository;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, SecurityHelper $security)
    {
        parent::__construct($validator, City::class, $entityManager, $security);
        $this->repository = $entityManager->getRepository(City::class);
        $this->stateRepository = $entityManager->getRepository(State::class);
    }

    public function updateCity(string $content, int $id): City {
        $city = $this->mapCity($content);
        $cityToUpdate = $this->repository->findOrFail($id);
        $cityToUpdate->setDescription($city->getDescription());
        $cityToUpdate->setState($city->getState());
        $this->repository->flush();
        return $cityToUpdate;
    }

    private function mapCity(string $content): City {
        $object = json_decode($content);
        $city = $this->deserialize($content, false);
        if(isset($object->stateId)) {
            $city->setState($this->stateRepository->findOrFail($object->stateId));
        }

//        $this->setCompany($city);

        return $city;
    }
}
