<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarCost
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CarCost
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
     * @ORM\ManyToOne(targetEntity="Car", inversedBy="costs")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    private $car;

    /**
     * @var integer
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="mileage", type="integer")
     */
    private $mileage;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     * @ORM\Column(name="currency", type="string", length=3)
     */
    private $currency;

     /**
     * @var float
     * @ORM\Column(name="exchangeRate", type="float", nullable=false)
     */
    private $exchangeRate;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    public $typesArray = null;


    public function __construct()
    {
        $this->typesArray = TranslationContainer::Instance()->carCostTypes;
    }

    public function getAmountInDefaultCurrency()
    {
        return $this->amount * $this->exchangeRate;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return CarCost
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return CarCost
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
     * Set mileage
     *
     * @param integer $mileage
     *
     * @return CarCost
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
     * Set amount
     *
     * @param float $amount
     *
     * @return CarCost
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
     * @return CarCost
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CarCost
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Car
     * @return CarBundle\Entity\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set Car
     * @param CarBundle\Entity\Car $car
     * @return CarCost
     */
    public function setCar(Car $car = null)
    {
        $this->car = $car;
        return $this;
	}

    public function getTypeName()
    {
        if($this->typesArray === null){
            $this->typesArray = TranslationContainer::Instance()->carCostTypes;
        }
        return $this->typesArray[$this->type]['name'];
    }

    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

}

