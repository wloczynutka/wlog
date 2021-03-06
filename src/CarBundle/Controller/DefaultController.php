<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \CarBundle\Entity\Car;
use \CarBundle\Entity\CarFueling;
use \CarBundle\Entity\CarCost;
use \CarBundle\Entity\CarImage;
use \CarBundle\Form\CarImageType;
use \CarBundle\Form\CarFuelingType;
use \CarBundle\Form\CarCostType;
use \CarBundle\Form\CarType;
use Symfony\Component\HttpFoundation\Request;
use CarBundle\Entity\TranslationContainer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use \AppBundle\Entity\User;

class DefaultController extends Controller
{

    public function homeAction()
    {
        return $this->render('CarBundle:Ramble:index.html.twig');
    }

    public function addCarAction(Request $request)
    {
		$car = new Car();
		$form = $this->createForm(new CarType(), $car);
		$form->handleRequest($request);
		if ($form->isValid()) {
            $user = $this->get('security.context')->getToken()->getUser();
            $car->setUser($user);
			$em = $this->getDoctrine()->getManager();
			$em->persist($car);
			$em->flush();
            return $this->redirectToRoute('car_show_car', ['carId' => $car->getId()]);
		}
        
		return $this->render('CarBundle:Default:addCar.html.twig', array('form' => $form->createView()));
    }

    public function listUserCarsAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $carList = $this->getDoctrine()
                ->getRepository('CarBundle:Car')
                ->findByUser($user)
            ;
        } else {
            $carList = $this->getDoctrine()
                ->getRepository('CarBundle:Car')
                ->findAll();
        }
        return $this->render('CarBundle:Ramble:listUserCars.html.twig', ['carList' => $carList]);
    }

    public function addCostAction(Request $request, $carId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $car = $this->loadCarById($carId);
        if(!$user instanceof User || $user !== $car->getUser()){ // no privileages to add cost for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }
        $carCost = new CarCost();
        $carCost
                ->setDateTime(new \DateTime)
                ->setCar($car)
                ->setCurrency($car->getDefaultCurrency())
                ->setType(10) //default cost type (tool road)
        ;
		$form = $this->createForm(new CarCostType(), $carCost);
		$form->handleRequest($request);
		if ($form->isValid()) {
            $carCost->setExchangeRate($this->retreiveExchangeRate($carCost->getCurrency(), $car->getDefaultCurrency()));
			$em = $this->getDoctrine()->getManager();
			$em->persist($carCost);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carId]);
		}

		return $this->render('CarBundle:Ramble:cost.html.twig', ['form' => $form->createView(), 'car' => $car, 'action' => $this->get('translator')->trans('Add cost')]);
    }

    public function addImageAction(Request $request, $carId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $car = $this->loadCarById($carId);
        if(!$user instanceof User || $user !== $car->getUser()){ // no privileages to add image for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }
        $carImage = new CarImage();
        $carImage
            ->setDateTime(new \DateTime())
            ->setCar($car)
        ;

        $form = $this->createForm(new CarImageType(), $carImage);
        $form->handleRequest($request);
        if ($form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $carImage->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('image_directory'), $fileName);
            $carImage->setFile($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($carImage);
            $em->flush();
            return $this->redirectToRoute('car_show_car', ['carId' => $carId]);
        }

        return $this->render('CarBundle:Ramble:image.html.twig', ['form' => $form->createView(), 'car' => $car, 'action' => $this->get('translator')->trans('Add image')]);
    }


    public function addFuelingAction(Request $request, $carId, $masterFuelingId)
	{
        $car = $this->loadCarById($carId);
        $user = $this->get('security.context')->getToken()->getUser();
        if(!$user instanceof User || $user !== $car->getUser()){ // no privileages to add cost for this car
            return $this->render('CarBundle:Ramble:error.html.twig', ['error' => TranslationContainer::Instance()->errorMessages[1]]);
        }
		$carFueling = new CarFueling();
		$carFueling
                ->setMasterFuelingId((int) $masterFuelingId)
                ->setDateTime(new \DateTime)
                ->setCurrency($car->getDefaultCurrency())
                ->setCar($car)
                ->setFuelType($car->getFuel());
        $this->updateMileageForPartialFuelings($carFueling);
		$form = $this->createForm(new CarFuelingType(), $carFueling);
		$form->handleRequest($request);
		if ($form->isValid()) {
            if($carFueling->getAmount() === null && $carFueling->getPricePerLiter() !== null){
                $carFueling->setAmount($carFueling->getPricePerLiter() * $carFueling->getLitresTanked());
            }
            if (null === $carFueling->getPricePerLiter() && null !== $carFueling->getAmount() && 0 != $carFueling->getLitresTanked()){
                $carFueling->setPricePerLiter($carFueling->getAmount() / $carFueling->getLitresTanked());
            }
            $carFueling->setExchangeRate($this->retreiveExchangeRate($carFueling->getCurrency(), $car->getDefaultCurrency()));
			$em = $this->getDoctrine()->getManager();
			$em->persist($carFueling);
			$em->flush();
			return $this->redirectToRoute('car_show_car', ['carId' => $carId]);
		}

		return $this->render('CarBundle:Ramble:fueling.html.twig', ['form' => $form->createView(), 'car' => $car, 'action' => $this->get('translator')->trans('Add fueling')]);
	}


    private function updateMileageForPartialFuelings(CarFueling $carFueling)
    {
        if(0 === $carFueling->getMasterFuelingId()){
            return;
        }
        $carFuelingMaster = $this->getDoctrine()
            ->getRepository('CarBundle:CarFueling')
            ->find($carFueling->getMasterFuelingId())
        ;
        $carFueling
                ->setMileage($carFuelingMaster->getMileage())
                ->setAverageConsumptionByComputer($carFuelingMaster->getAverageConsumptionByComputer())
                ->setAverageSpeed($carFuelingMaster->getAverageSpeed())
                ->setDriveTime($carFuelingMaster->getDriveTime())
                ->setTripDistance($carFuelingMaster->getTripDistance())
        ;
    }

    private function retreiveExchangeRate($currencyFrom, $currencyTo)
    {
        $currencyController = new CurrencyController();
        $exchangeRate = $currencyController->getExchangeRate($currencyFrom, $currencyTo);
        return $exchangeRate;
    }

    public function showCarAction($carId)
    {
		$car = $this->loadCarById($carId);
        return $this->render('CarBundle:Ramble:car.html.twig', array('car' => $car));
    }

    public function getStatImageAction($carId)
    {
        $car = $this->loadCarById($carId);
        $tplpath = __DIR__ . '/../Resources/imagestats/tourneo4wlog.jpg';
        $im = imagecreatefromjpeg($tplpath);
        $clrBorder = ImageColorAllocate($im, 70, 70, 70);
        $black = ImageColorAllocate($im, 0, 0, 0);
        $darkGreen = ImageColorAllocate($im, 100, 255, 100);
        $fontfile = __DIR__ . "/../Resources/imagestats/verdana.ttf";
        $fontsize = 8;
        ImageTTFText($im, $fontsize, 0, 5, 12, $black, $fontfile, $this->generateConsumptionTxt($car));
        ImageTTFText($im, $fontsize, 0, 5, 24, $black, $fontfile, $this->get('translator')->trans('Mileage: ') . $car->getMileage() . ' [km]');
        ImageTTFText($im, $fontsize, 0, 5, 36, $black, $fontfile, $this->generateTotLitresTxt($car));
        ImageTTFText($im, $fontsize, 0, 5, 48, $black, $fontfile, $car->getOwnName());
        $CarNameText = $car->getMake()->getName() . ' ' . $car->getModel()->getName();
        $dimensions = imagettfbbox(7, 0, $fontfile, $CarNameText);
        $textWidth = abs($dimensions[4] - $dimensions[0]);
        $x = imagesx($im) - $textWidth-5;
        ImageTTFText($im, 7, 0, $x, 46, $darkGreen, $fontfile, $CarNameText);
        ImageRectangle($im, 0, 0, imagesx($im) - 1, imagesy($im) - 1, $clrBorder);
        $imageFile = __DIR__ . '/../Resources/public/images/carstats/car'.$car->getId().'.jpg';
        Imagejpeg($im, $imageFile, 90);
        ImageDestroy($im);
        $response = new BinaryFileResponse($imageFile);
        return $response;
    }

    private function generateConsumptionTxt($car)
    {
        $text = $this->get('translator')->trans('Avg Consumption: ') . $car->getAverageFuelConsumption();
        $text .= ' (' . $this->get('translator')->trans('comp: ').$car->getAverageFuelConsumptionByObd().')';
        $text .= ' [L/100km]' ;
        return $text;
    }

    private function generateTotLitresTxt($car)
    {
        $totLitresTxt = $this->get('translator')->trans('Tanked volume: ') . $car->getTotalTankedLitres() . ' [L]';
        $totLitresTxt .= ' (' . TranslationContainer::Instance()->fuelTypes[$car->getFuel()]['name'] . ')';
        return $totLitresTxt;
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
