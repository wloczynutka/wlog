<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;

class DefaultController extends Controller
{
    public function indexAction($carId)
    {
		$car = $this->loadCarById($carId);
		$costs = $car->getCosts();
		foreach ($costs as $key => $value) {
			d($value);
		}
		d($carId, $car, $costs);
        return $this->render('CarBundle:Default:car.html.twig', array('car' => $car));
    }

	private function loadCarById($carId)
    {
        $car = $this->getDoctrine()
            ->getRepository('CarBundle:Car')
            ->find($carId)
        ;
        return $car;
    }
}
