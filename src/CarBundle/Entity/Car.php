<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Car
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Car
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="make", type="integer")
     */
    private $make;

    /**
     * @var integer
     *
     * @ORM\Column(name="model", type="integer")
     */
    private $model;

    /**
     * @var integer
     *
     * @ORM\Column(name="user", type="integer")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="manufactureDate", type="date")
     */
    private $manufactureDate;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @var integer
     *
     * @ORM\Column(name="fuel", type="integer")
     */
    private $fuel;

	/**
     * @ORM\OneToMany(targetEntity="CarCost", mappedBy="car")
     */
    private $costs;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set make
     *
     * @param integer $make
     *
     * @return Car
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return integer
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set model
     *
     * @param integer $model
     *
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return integer
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Car
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set manufactureDate
     *
     * @param \DateTime $manufactureDate
     *
     * @return Car
     */
    public function setManufactureDate($manufactureDate)
    {
        $this->manufactureDate = $manufactureDate;

        return $this;
    }

    /**
     * Get manufactureDate
     *
     * @return \DateTime
     */
    public function getManufactureDate()
    {
        return $this->manufactureDate;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Car
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set fuel
     *
     * @param integer $fuel
     *
     * @return Car
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * Get fuel
     *
     * @return integer
     */
    public function getFuel()
    {
        return $this->fuel;
    }

   /**
     * Add descriptions
     *
     * @param CarCost $cost
     * @return Car
     */
    public function addCost(CarCost $cost)
    {
        $this->costs[] = $cost;
        return $this;
    }

    /**
     * Remove place
     * @param CarCost $cost
     */
    public function removeCost(CarCost $cost)
    {
        $this->costs->removeElement($cost);
    }

    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCosts()
    {
        return $this->costs;
    }

}

