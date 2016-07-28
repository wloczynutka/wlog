<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CarBundle\Entity\Car;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="CarBundle\Entity\Car", mappedBy="user")
     */
    protected $cars;


    public function __construct()
    {
        $this->cars = new ArrayCollection();
        parent::__construct();
    }

   /**
     * Add Car
     *
     * @param Car $car
     * @return Car
     */
    public function addCar(Car $car)
    {
        $this->cars[] = $car;
        return $this;
    }

    /**
     * Remove Car
     * @param Car $car
     */
    public function removeCost(Car $car)
    {
        $this->car->removeElement($car);
    }

    /**
     * Get Cars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCosts()
    {
        return $this->cars;
    }
}
