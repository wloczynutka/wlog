<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CarBundle\Entity\CarDictionaryMake;
use AppBundle\Entity\User;
use CarBundle\Entity\CarImage;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="cars")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
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

    /**
     * @ORM\OneToMany(targetEntity="CarImage", mappedBy="car")
     */
    private $images;

    private $allCostAmount = 0;

    private $totalFuelCosts = 0;

    private $averageFuelConsumption = 0;
    
    private $averageFuelConsumptionByObd = 0;

    private $mileage = 0;

    private $totalTankedLitres = 0;

    private $costSummary;

    /**
     * @var \DateTime
     */
    private $lastFuelingDate;

    /**
     * @var string
     * @ORM\Column(name="defaultCurrency", type="string", length=3)
     */
    private $defaultCurrency;

    public function __construct()
    {
        $this->costs = new ArrayCollection();
        $this->fuelings = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->initiateCostSummary();
    }

    private function initiateCostSummary()
    {
        $this->costSummary = new CostSummary();
        $this->costSummary->setCostCollection($this->costs);
        $this->lastFuelingDate = new \DateTime('0001-01-01');
    }

    /**
     * initiate costSummary object when entity loaded from db. (__construct not called)
     */
    public function __postLoad()
    {
        if($this->costSummary === null){
            $this->initiateCostSummary();
        }
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
     * @param integer $model
     * @return Car
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     * @return CarDictionaryModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set user
     * @param User $user
     * @return Car
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
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


    /**
     * Add CarImage
     *
     * @param CarImage $image
     * @return Car
     */
    public function addImage(CarImage $image)
    {
        $this->images[] = $image;
        return $this;
    }

    /**
     * Remove CarImage
     * @param CarImage $image
     */
    public function removeImage(CarImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get CarImage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }


    private function processPartialFuelings()
    {
        $masterFuelings = new ArrayCollection();
         /* @var $carFueling  \CarBundle\Entity\CarFueling */
         /* @var $masterFueling  \CarBundle\Entity\CarFueling */
        foreach ($this->fuelings as $carFueling){
            $this->checkAndSetLastFuelingDate($carFueling);
            if($carFueling->getMasterFuelingId() !== 0){
                $masterFueling = $this->findFuelingById($carFueling->getMasterFuelingId());
                $masterFueling->addPartialFueling($carFueling);
                $this->addMasterFuelingToCollectionIfNotExistYet($masterFuelings, $masterFueling);
                $this->fuelings->removeElement($carFueling);
            }
        }
        foreach ($masterFuelings as $masterFueling) {
            $masterFueling
                ->addPartialFueling(clone $masterFueling)
                ->setLitresTanked(0)
                ->setPricePerLiter(0)
                ->setAmount(0)
            ;
        }
        $this->recalculatePartialFuelings();
    }

    private function checkAndSetLastFuelingDate(\CarBundle\Entity\CarFueling $carFueling)
    {
        if($carFueling->getDateTime() > $this->lastFuelingDate){
            $this->lastFuelingDate = $carFueling->getDateTime();
        }
    }

    private function addMasterFuelingToCollectionIfNotExistYet(ArrayCollection $masterFuelings, \CarBundle\Entity\CarFueling $masterFueling)
    {
        if(false === $masterFuelings->indexOf($masterFueling)){
            $masterFuelings->add($masterFueling);
        }
    }

    private function recalculatePartialFuelings()
    {
         /* @var $carFueling  \CarBundle\Entity\CarFueling */
         /* @var $partFueling  \CarBundle\Entity\CarFueling */
        foreach ($this->fuelings as $carFueling){
            if(count($carFueling->getPartialFuelings()) > 0){
                $this->recalculateFuelingWithPartials($carFueling);
            }
        }
    }

    private function recalculateFuelingWithPartials(\CarBundle\Entity\CarFueling $carFueling)
    {
        foreach ($carFueling->getPartialFuelings() as $partFueling) {
            $carFueling->setLitresTanked($carFueling->getLitresTanked() + $partFueling->getLitresTanked());
            $carFueling->setAmount($carFueling->getAmount() + $partFueling->getAmountInDefaultCurrency());
        }

    }

    private function findFuelingById($fuelingId)
    {
        foreach ($this->fuelings as $carFueling){
            if($carFueling->getId() === $fuelingId){
                return $carFueling;
            }
        }
    }

	private function calculateAllCosts()
	{
        $this->processPartialFuelings();
        $this->totalFuelCosts = 0;
        $this->totalTankedLitres = 0;
        
		/* @var $carCost \CarBundle\Entity\CarCost */
		foreach ($this->costs as $carCost) {
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
            $this->totalFuelCosts += $carFueling->getAmountInDefaultCurrency();
            $this->totalTankedLitres += $carFueling->getLitresTanked();
            $this->checkAndSetMileage($carFueling->getMileage());
            if($carFueling->getAverageConsumptionByComputer() != 0){
                $fuelConsumptionObdSum += $carFueling->getAverageConsumptionByComputer();
                $fuelConsumptionObdSumCount++;
            }
            $fuelConsumptionSum += $carFueling->getFuelConsumptionFromPrievous();
		}
        count($this->fuelings) > 1 ? $this->averageFuelConsumption = round($fuelConsumptionSum/(count($this->fuelings)-1),2) : 0;
        $this->allCostAmount = $this->costSummary->getAllCostSum() + $this->totalFuelCosts;
        if($fuelConsumptionObdSumCount === 0){
            $this->averageFuelConsumptionByObd = 0;
        } else {
            $this->averageFuelConsumptionByObd = $fuelConsumptionObdSum / $fuelConsumptionObdSumCount;
        }
	}

    /**
     * @return \CarBundle\Entity\CarImage
     */
    public function getAvatar()
    {
        /* @var $image  \CarBundle\Entity\CarImage */
        foreach ($this->images as $image) {
            if($image->isIsAvatar()){
                return $image;
            }
        }
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
        return round($this->averageFuelConsumption, 2);
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
        return round($this->averageFuelConsumptionByObd, 2);
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

    public function getCostSummary()
    {
        return $this->costSummary;
    }

    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency($defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;
        return $this;
    }

    public function getTimeIntervalSincelastFueling()
    {
        return $this->lastFuelingDate->diff(new \DateTime);
    }
}

