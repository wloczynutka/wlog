<?php

namespace CarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of CostSummary
 *
 * @author Łza
 */
class CostSummary
{
    /**
     *
     * @var ArrayCollection
     */
    private $costCollection;

    private $tax = 0;
    private $reperair = 0;
    private $periodService = 0;
    private $accesory = 0;
    private $parts = 0;
    private $insurance = 0;
    private $other = 0;
    private $camperConversion = 0;
    private $technicalExamination = 0;

    private $allCostSum = null;


    public function __construct()
    {
        $this->costCollection = new ArrayCollection;
    }

    private function calculateCostsIfNeccesary()
    {
        if($this->allCostSum === null){
            $this->calculateCosts();
        }
    }
    
    private function calculateCosts()
    {
        /* @var $carCost CarCost */
		foreach ($this->costCollection as $carCost) {
            switch ($carCost->getType()) {
                case 1:
                    $this->reperair += $carCost->getAmount();
                    break;
                case 2:
                    $this->periodService += $carCost->getAmount();
                    break;
                case 3:
                    $this->accesory += $carCost->getAmount();
                    break;
                case 4:
                    $this->parts += $carCost->getAmount();
                    break;
                case 5:
                    $this->insurance += $carCost->getAmount();
                    break;
                case 6:
                    $this->tax += $carCost->getAmount();
                    break;
                case 7:
                    $this->other += $carCost->getAmount();
                    break;
                case 8:
                    $this->camperConversion += $carCost->getAmount();
                    break;
                case 9:
                    $this->technicalExamination += $carCost->getAmount();
                    break;

                default:
                    ddd('type unknown');
            }
            $this->allCostSum += $carCost->getAmount();
        }
    }

    public function setCostCollection($costCollection)
    {
        $this->costCollection = $costCollection;
        return $this;
    }

    public function getTax()
    {
        $this->calculateCostsIfNeccesary();
        return $this->tax;
    }

    public function getReperair()
    {
        $this->calculateCostsIfNeccesary();
        return $this->reperair;
    }

    public function getPeriodService()
    {
        $this->calculateCostsIfNeccesary();
        return $this->periodService;
    }

    public function getParts()
    {
        $this->calculateCostsIfNeccesary();
        return $this->parts;
    }

    public function getInsurance()
    {
        $this->calculateCostsIfNeccesary();
        return $this->insurance;
    }

    public function getOther()
    {
        $this->calculateCostsIfNeccesary();
        return $this->other;
    }

    public function getCamperConversion()
    {
        $this->calculateCostsIfNeccesary();
        return $this->camperConversion;
    }

    public function getTechnicalExamination()
    {
        $this->calculateCostsIfNeccesary();
        return $this->technicalExamination;
    }

    public function getAllCostSum()
    {
        $this->calculateCostsIfNeccesary();
        return $this->allCostSum;
    }
    
    public function getAccesory()
    {
        $this->calculateCostsIfNeccesary();
        return $this->accesory;
    }


}
