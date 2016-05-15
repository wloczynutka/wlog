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

class EditController extends Controller
{
    public function editCarAction(Request $request, $carId)
    {
		$car = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
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

    public function editCostAction(Request $request, $costId)
    {
        $carCost = $this->loadCostById($costId);
        d($carCost);
		$form = $this->createForm(new CarCostType(), $carCost);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($carCost);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carCost->getCar()->getId()]);
		}
        
		return $this->render('CarBundle:Default:fueling.html.twig', array('form' => $form->createView()));
    }

    public function editFuelingAction(Request $request, $fuelingId)
	{

        $carFueling = $this->getDoctrine()
            ->getRepository('CarBundle:CarFueling')
            ->find($fuelingId)
        ;
		$form = $this->createForm(new CarFuelingType(), $carFueling);
		$form->handleRequest($request);
		if ($form->isValid()) {
            if($carFueling->getAmount() === null && $carFueling->getPricePerLiter() != null){
                $carFueling->setAmount($carFueling->getPricePerLiter() * $carFueling->getLitresTanked());
            }

			$em = $this->getDoctrine()->getManager();
			$em->persist($carFueling);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carFueling->getCar()->getId()]);
		}
        
		return $this->render('CarBundle:Default:fueling.html.twig', array('form' => $form->createView()));
	}

	/**
	 *
	 * @param integer $costId
	 * @return CarCost
	 */
	private function loadCostById($costId)
    {
        $carCost = $this->getDoctrine()
            ->getRepository('CarBundle:CarCost')
            ->find($costId)
        ;
        return $carCost;
    }
}
