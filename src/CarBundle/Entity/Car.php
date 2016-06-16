<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CarBundle\Entity\CarDictionaryMake;

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
     * @ORM\ManyToOne(targetEntity="CarDictionaryMake", inversedBy="name")
     * @ORM\JoinColumn(name="make", referencedColumnName="id")
     */
    private $make;

    /**
     * @ORM\ManyToOne(targetEntity="CarDictionaryModel", inversedBy="name")
     * @ORM\JoinColumn(name="model", referencedColumnName="id")
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
     * @ORM\OrderBy({"dateTime" = "ASC"})
     */
    private $costs;

	/**
     * @ORM\OneToMany(targetEntity="CarFueling", mappedBy="car")
     * @ORM\OrderBy({"dateTime" = "ASC"})
     */
    private $fuelings;

    /**
     * @var float
     * @ORM\Column(name="purchase_price", type="float", nullable=true)
     *
     */
    private $purchasePrice;

    /**
     * @var string
     * @ORM\Column(name="ownName", type="string", length=33)
     */
    private $ownName;

    private $allCostAmount = 0;

    private $totalFuelCosts = 0;

    private $averageFuelConsumption = 0;
    
    private $averageFuelConsumptionByObd = 0;

    private $mileage = 0;

    private $totalTankedLitres = 0;
/*
    public static $fuelTypes = [
        1 => [
            'name' => 'petrol'
        ],
        2 => [
            'name' => 'diesel'
        ],
        3 => [
            'name' => 'LPG'
        ],
    ];
*/
    public function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->fuelings = new ArrayCollection();
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
     * @return CarDictionaryMake
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
     * @return CarDictionaryModel
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
     * @param \DateTime $manufactureDate
     * @return Car
     */
    public function setManufactureDate($manufactureDate)
    {
        $this->manufactureDate = $manufactureDate;

        return $this;
    }

    /**
     * Get manufactureDate
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
     * Add Costs
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
     * Remove Costs
     * @param CarCost $cost
     */
    public function removeCost(CarCost $cost)
    {
        $this->costs->removeElement($cost);
    }

    /**
     * Get Costs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCosts()
    {
        return $this->costs;
    }


   /**
     * Add Fueling
     *
     * @param CarFueling $fueling
     * @return Car
     */
    public function addFueling(CarFueling $fueling)
    {
        $this->fuelings[] = $fueling;
        return $this;
    }

    /**
     * Remove Fueling
     * @param CarFueling $fueling
     */
    public function removeFueling(CarFueling $fueling)
    {
        $this->fuelings->removeElement($fueling);
    }

    /**
     * Get Fuelings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFuelings()
    {
        return $this->fuelings;
    }

	private function calculateAllCosts()
	{
		$allCostAmount = 0;
        $this->totalFuelCosts = 0;
        $this->totalTankedLitres = 0;
        
		/* @var $carCost CarBundle\Entity\CarCost */
		foreach ($this->costs as $carCost) {
			$allCostAmount += $carCost->getAmount();
            $this->checkAndSetMileage($carCost->getMileage());
		}
        
        /* @var $carFueling \CarBundle\Entity\CarFueling */
        $prievousFueling = false;
        $fuelConsumptionSum = $fuelConsumptionObdSum = $fuelConsumptionObdSumCount = 0;

		foreach ($this->fuelings as $carFueling) {
            if($prievousFueling instanceof CarFueling){
                $carFueling->caclulateFuelConsumption($prievousFueling);
            }
            $prievousFueling = $carFueling;
            $this->totalFuelCosts += $carFueling->getAmount();
            $this->totalTankedLitres += $carFueling->getLitresTanked();
            $this->checkAndSetMileage($carFueling->getMileage());
            if($carFueling->getAverageConsumptionByComputer() != 0){
                $fuelConsumptionObdSum += $carFueling->getAverageConsumptionByComputer();
                $fuelConsumptionObdSumCount++;
            }
            $fuelConsumptionSum += $carFueling->getFuelConsumptionFromPrievous();
		}
        count($this->fuelings) > 1 ? $this->averageFuelConsumption = round($fuelConsumptionSum/(count($this->fuelings)-1),2) : 0;
		$this->allCostAmount = $allCostAmount + $this->totalFuelCosts;
        $this->averageFuelConsumptionByObd = $fuelConsumptionObdSum / $fuelConsumptionObdSumCount;
	}

    private function checkAndSetMileage($newMileage)
    {
        if($newMileage > $this->mileage){
            $this->mileage = $newMileage;
        }
    }

    /**
     *
     * @return float
     */
    public function getAllCostAmount()
    {
        if($this->allCostAmount === 0){
            $this->calculateAllCosts();
        }
        return $this->allCostAmount;
    }

    public function getTotalFuelCosts()
    {
        if($this->totalFuelCosts === 0){
           $this->calculateAllCosts();
        }
        return $this->totalFuelCosts;
    }

    public function getTotalTankedLitres()
    {
        if($this->totalTankedLitres === 0){
            $this->calculateAllCosts();
        }

        return $this->totalTankedLitres;
    }

    public function getMileage()
    {
        if($this->mileage === 0){
           $this->calculateAllCosts();
        }
        return $this->mileage;
    }

    public function getAverageFuelConsumption()
    {
        if($this->averageFuelConsumption === 0){
           $this->calculateAllCosts();
        }
        return $this->averageFuelConsumption;
    }
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }
    
    public function getAverageFuelConsumptionByObd()
    {
        return $this->averageFuelConsumptionByObd;
    }

    public function getOwnName()
    {
        return $this->ownName;
    }

    public function setOwnName($ownName)
    {
        $this->ownName = $ownName;
        return $this;
    }

}

