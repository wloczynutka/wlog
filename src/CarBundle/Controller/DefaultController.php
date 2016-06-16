<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;
use \CarBundle\Entity\CarFueling;
use \CarBundle\Entity\CarCost;
use \CarBundle\Form\CarFuelingType;
use \CarBundle\Form\CarCostType;
use \CarBundle\Form\CarType;
use Symfony\Component\HttpFoundation\Request;
use CarBundle\Entity\TranslationContainer;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DefaultController extends Controller
{

    public function homeAction()
    {
        return $this->render('CarBundle:Default:home.html.twig');
    }

    public function addCarAction(Request $request)
    {
		$car = new Car();
		$form = $this->createForm(new CarType(), $car);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($car);
			$em->flush();
            return $this->redirectToRoute('car_show_car', ['carId' => $car->getId()]);
		}
        
		return $this->render('CarBundle:Default:addCar.html.twig', array('form' => $form->createView()));
    }

    public function listAllCarsAction(Request $request)
    {
         $carList = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->findAll()
        ;
        return $this->render('CarBundle:Default:list.html.twig', ['carList' => $carList]);
    }

    public function addCostAction(Request $request, $carId)
    {
        $car = $this->loadCarById($carId);
        $carCost = new CarCost();
        $carCost
                ->setDateTime(new \DateTime)
                ->setCar($car)
                ->setCurrency('PLN');
		$form = $this->createForm(new CarCostType(), $carCost);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($carCost);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carId]);
		}

		return $this->render('CarBundle:Default:fueling.html.twig', array('form' => $form->createView()));
    }

    public function addFuelingAction(Request $request, $carId)
	{
        $car = $this->loadCarById($carId);
		$carFueling = new CarFueling();
		$carFueling
                ->setDateTime(new \DateTime)
                ->setCurrency('PLN')
                ->setCar($car)
                ->setFuelType($car->getFuel());
		$form = $this->createForm(new CarFuelingType(), $carFueling);
		$form->handleRequest($request);
		if ($form->isValid()) {
            if($carFueling->getAmount() === null && $carFueling->getPricePerLiter() != null){
                $carFueling->setAmount($carFueling->getPricePerLiter() * $carFueling->getLitresTanked());
            }

			$em = $this->getDoctrine()->getManager();
			$em->persist($carFueling);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carId]);
		} 
        
		return $this->render('CarBundle:Default:fueling.html.twig', array('form' => $form->createView()));
	}

    public function showCarAction($carId)
    {
		$car = $this->loadCarById($carId);
        return $this->render('CarBundle:Default:car.html.twig', array('car' => $car));
    }

    public function getStatImageAction($carId)
    {
        $car = $this->loadCarById($carId);


        $tplpath = __DIR__ . '/../Resources/imagestats/tourneo4wlog.jpg';
        $maxtextwidth = 60;

        $im = imagecreatefromjpeg($tplpath);
        $clrBorder = ImageColorAllocate($im, 70, 70, 70);
        $black = ImageColorAllocate($im, 0, 0, 0);
        $white = ImageColorAllocate($im, 255, 255, 255);
        $darkGreen = ImageColorAllocate($im, 100, 255, 100);

        $fontfile = __DIR__ . "/../Resources/imagestats/verdana.ttf";
        $fontsize = 8;

        $text = $this->get('translator')->trans('Avg Consumption: ') . $car->getAverageFuelConsumption();
        $text .= ' (' . $this->get('translator')->trans('comp: ').$car->getAverageFuelConsumptionByObd().')' . ' [L/100km]' ;
        ImageTTFText($im, $fontsize, 0, 5, 12, $black, $fontfile, $text);

        $text2 = $this->get('translator')->trans('Mileage: ') . $car->getMileage() . ' [km]';
        ImageTTFText($im, $fontsize, 0, 5, 24, $black, $fontfile, $text2);

        $totLitresTxt = $this->get('translator')->trans('Tanked volume: ') . $car->getTotalTankedLitres() . ' [L]';
        $totLitresTxt .= ' (' . TranslationContainer::Instance()->fuelTypes[$car->getFuel()]['name'] . ')';
        ImageTTFText($im, $fontsize, 0, 5, 36, $black, $fontfile, $totLitresTxt);

        ImageTTFText($im, $fontsize, 0, 5, 48, $black, $fontfile, $car->getOwnName());

        $CarNameText = $car->getMake()->getName() . ' ' . $car->getModel()->getName();

        $dimensions = imagettfbbox(7, 0, $fontfile, $CarNameText);
        $textWidth = abs($dimensions[4] - $dimensions[0]);
        $x = imagesx($im) - $textWidth-5;
        ImageTTFText($im, 7, 0, $x, 46, $darkGreen, $fontfile, $CarNameText);

        // draw border
        ImageRectangle($im, 0, 0, imagesx($im) - 1, imagesy($im) - 1, $clrBorder);
        // write output
        $imageFile = __DIR__ . '/../Resources/public/images/carstats/car'.$car->getId().'.jpg';
        Imagejpeg($im, $imageFile, 90);
        ImageDestroy($im);

        $response = new BinaryFileResponse($imageFile);
        return $response;
    }

	/**
	 *
	 * @param type $carId
	 * @return Car
	 */
	private function loadCarById($carId)
    {
        $car = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
        return $car;
    }
}
