<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;
use CarBundle\Entity\CarFueling;
use \CarBundle\Form\CarFuelingType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function addFuelingAction(Request $request, $carId)
	{
		d($carId);


		$carFueling = new CarFueling();
		$carFueling->setDateTime(new \DateTime);
//		$carFueling->setStartDateTime(new \DateTime());
//		$carFueling->setEndDateTime(new \DateTime());



		$form = $this->createForm(new CarFuelingType(), $carFueling);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($carFueling);
			$em->flush();
			return $this->redirectToRoute('/car/show/'.$carId);
		} else {
            d('form not validated');
		}


		return $this->render('CarBundle:Default:fueling.html.twig', array('form' => $form->createView()));
	}

    public function indexAction($carId)
    {
		$car = $this->loadCarById($carId);
		$costs = $car->calculateAllCosts();

		d($carId, $car, $costs);
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
