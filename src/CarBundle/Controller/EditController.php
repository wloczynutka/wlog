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
use AppBundle\Entity\User;

class EditController extends Controller
{
    public function editCarAction(Request $request, $carId)
    {
		$car = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user instanceof User || $user !== $car->getUser()){ // no privileages to add cost for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }
		$form = $this->createForm(new CarType(), $car);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($car);
			$em->flush();
            return $this->redirectToRoute('car_show_car', ['carId' => $car->getId()]);
		} 
		return $this->render('CarBundle:Ramble:addEditCar.html.twig', ['form' => $form->createView(), 'action' => $this->get('translator')->trans('Edit Car')]);
    }

    public function editCostAction(Request $request, $costId)
    {
        $carCost = $this->loadCostById($costId);
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user instanceof User || $user !== $carCost->getCar()->getUser()){ // no privileages to add cost for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }

		$form = $this->createForm(new CarCostType(), $carCost);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($carCost);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carCost->getCar()->getId()]);
		}
        
		return $this->render('CarBundle:Ramble:cost.html.twig', ['form' => $form->createView(), 'car' => $carCost->getCar(), 'action' => $this->get('translator')->trans('Edit cost')]);
    }

    public function editFuelingAction(Request $request, $fuelingId)
	{
        $carFueling = $this->getDoctrine()
            ->getRepository('CarBundle:CarFueling')
            ->find($fuelingId)
        ;
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user instanceof User || $user !== $carFueling->getCar()->getUser()){ // no privileages to add cost for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }

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
        
		return $this->render('CarBundle:Ramble:fueling.html.twig', ['form' => $form->createView(), 'car' => $carFueling->getCar(), 'action' => $this->get('translator')->trans('Edit fueling')]);
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
