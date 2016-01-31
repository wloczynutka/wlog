<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarFueling
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CarFueling
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var float
     *
     * @ORM\Column(name="litresTanked", type="float")
     */
    private $litresTanked;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

    /**
     * @var integer
     *
     * @ORM\Column(name="mileage", type="integer")
     */
    private $mileage;

    /**
     * @var integer
     *
     * @ORM\Column(name="fuelType", type="integer")
     */
    private $fuelType;

    /**
     * @var float
     *
     * @ORM\Column(name="computerAerageConsumption", type="float")
     */
    private $computerAerageConsumption;


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
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return CarFueling
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set litresTanked
     *
     * @param float $litresTanked
     *
     * @return CarFueling
     */
    public function setLitresTanked($litresTanked)
    {
        $this->litresTanked = $litresTanked;

        return $this;
    }

    /**
     * Get litresTanked
     *
     * @return float
     */
    public function getLitresTanked()
    {
        return $this->litresTanked;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return CarFueling
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return CarFueling
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set mileage
     *
     * @param integer $mileage
     *
     * @return CarFueling
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return integer
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set fuelType
     *
     * @param integer $fuelType
     *
     * @return CarFueling
     */
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * Get fuelType
     *
     * @return integer
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * Set computerAerageConsumption
     *
     * @param float $computerAerageConsumption
     *
     * @return CarFueling
     */
    public function setComputerAerageConsumption($computerAerageConsumption)
    {
        $this->computerAerageConsumption = $computerAerageConsumption;

        return $this;
    }

    /**
     * Get computerAerageConsumption
     *
     * @return float
     */
    public function getComputerAerageConsumption()
    {
        return $this->computerAerageConsumption;
    }
}

