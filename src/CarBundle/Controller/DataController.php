<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;
use \CarBundle\Entity\CarFueling;
use \CarBundle\Entity\CarCost;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class DataController extends Controller
{
    private $csvDelimeter = ';';
    private $dateFormat = 'Y-m-d H:i:s';

    public function exportAction(Request $request, $carId)
    {
        $car = $this->loadCar($carId);
        $csvString = $this->generateFuelingsString($car);
        $csvString .= $this->generateEmptyRows();
        $csvString .= $this->generateCostString($car);

        $response = new \Symfony\Component\HttpFoundation\Response($csvString);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$car->getMake()->getName().$car->getModel()->getName().'.csv"');
        return $response;
    }

    /**
     * 
     * @param Car $car
     * @return string
     */
    private function generateCostString(Car $car)
    {
        $csvString = $this->generateCostsHeaderString();
        /* @var $cost CarCost */
        foreach ($car->getCosts() as $cost) {
            $csvString .= implode($this->csvDelimeter, [
                $cost->getDateTime()->format($this->dateFormat),
                $cost->getMileage(),
                $cost->getTypeName(),
                '',
                $cost->getAmount(),
                $cost->getCurrency(),
                $cost->getDescription(),
            ]) . "\n";
        }
        return $csvString;
    }

    /**
     *
     * @return string
     */
    private function generateCostsHeaderString()
    {
        $csvString = implode($this->csvDelimeter, [
            $this->get('translator')->trans('Date'),
            $this->get('translator')->trans('Odometer'),
            $this->get('translator')->trans('Type'),
            '',
            $this->get('translator')->trans('Paid amount'),
            $this->get('translator')->trans('Currency'),
            $this->get('translator')->trans('Description'),
        ])   . "\n";
        return $csvString;
    }

    private function generateFuelingsHeaderString()
    {
        $csvString = implode($this->csvDelimeter, [
            $this->get('translator')->trans('Date'),
            $this->get('translator')->trans('Odometer'),
            $this->get('translator')->trans('Litres'),
            $this->get('translator')->trans('Price/liter'),
            $this->get('translator')->trans('Paid amount'),
            $this->get('translator')->trans('Currency'),
            $this->get('translator')->trans('Exchange rate'),
            $this->get('translator')->trans('Fuel Type'),
            $this->get('translator')->trans('Average consumption by computer'),
            $this->get('translator')->trans('Average speed'),
            $this->get('translator')->trans('Driving time'),
        ]) . "\n";
        return $csvString;
    }

    private function generateFuelingsString(Car $car)
    {
        $csvString = $this->generateFuelingsHeaderString();
        /* @var $fueling CarFueling */
        foreach ($car->getFuelings() as $fueling) {
            $csvString .= implode($this->csvDelimeter, [
                $fueling->getDateTime()->format($this->dateFormat),
                $fueling->getMileage(),
                $fueling->getLitresTanked(),
                $fueling->getPricePerLiter(),
                $fueling->getAmount(),
                $fueling->getCurrency(),
                $fueling->getExchangeRate(),
                $fueling->getFuelType(),
                $fueling->getAverageConsumptionByComputer(),
                $fueling->getAverageSpeed(),
                $fueling->getDriveTime(),
            ]) . "\n";
        }
        return $csvString;
    }

    private function generateEmptyRows($nrOfRows = 3)
    {
        $csvString = '';
        for ($i=1; $i <= $nrOfRows; $i++){
            $csvString .= "\n";
        }
        return $csvString;
    }

    /**
     * @param type $carId
     * @return Car
     */
    private function loadCar($carId)
    {
		$car = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
        return $car;
    }
}
