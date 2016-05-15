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
//        return $this->render('CarBundle:Default:main.html.twig', array('car' => $car));
        return $this->render('CarBundle:Default:car.html.twig', array('car' => $car));
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
