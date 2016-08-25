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

        foreach ($car->getFuelings() as $fueling) {
            if($fueling->getCurrency() === $car->getDefaultCurrency()) {
                $chartsData['fuelPriceChart'][] = [$fueling->getDateTime()->format('Y-m-d'), $fueling->getPricePerLiter()];
                if($fueling->getAverageSpeed() != null) {
                    $chartsData['avgSpeedConsumption'][] = [$fueling->getAverageSpeed(), $fueling->getAverageConsumptionByComputer()];
                }
            }
        }
        d($chartsData, $fueling);

        return $this->render('CarBundle:Ramble:charts.html.twig', ['car' => $car, 'chartsData' => $chartsData]);
    }
}