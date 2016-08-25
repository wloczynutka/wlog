<?php

namespace CarBundle\Service;


class CarService
{
    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }


    /**
     *
     * @param type $carId
     * @return \CarBundle\Entity\Car
     */
    public function loadCarById($carId)
    {
        $car = $this->entityManager
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
        return $car;
    }


}