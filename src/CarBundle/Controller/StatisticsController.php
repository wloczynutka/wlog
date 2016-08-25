<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;
use Symfony\Component\HttpFoundation\Request;

class StatisticsController extends Controller
{
    public function drawChartsAction(Request $request, $carId)
    {
        $car = $this->get('car_service')->loadCarById($carId);
        /* @var $fueling \CarBundle\Entity\CarFueling */
        foreach ($car->getFuelings() as $fueling) {
            if($fueling->getCurrency() === $car->getDefaultCurrency()) {
                $chartsData['fuelPriceChart'][] = [$fueling->getDateTime()->format('Y-m-d'), $fueling->getPricePerLiter()];
                if($fueling->getAverageSpeed() != null) {
                    $chartsData['avgSpeedConsumption'][] = [$fueling->getAverageSpeed(), $fueling->getAverageConsumptionByComputer()];
                }
                if(!isset($chartsData['monthDistance'][$fueling->getDateTime()->format('Y-m')])){
                    $chartsData['monthDistance'][$fueling->getDateTime()->format('Y-m')] = 0;
                }
                $chartsData['monthDistance'][$fueling->getDateTime()->format('Y-m')] =  $chartsData['monthDistance'][$fueling->getDateTime()->format('Y-m')] + $fueling->getTripDistance();
            }
        }
        d($chartsData, $fueling);

        return $this->render('CarBundle:Ramble:charts.html.twig', ['car' => $car, 'chartsData' => $chartsData]);
    }
}