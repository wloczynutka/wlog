<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AppBundle\Entity\Travel;
use \AppBundle\Entity\Place;
use \AppBundle\Form\TravelType;
use \AppBundle\Form\PlaceType;
use \AppBundle\Engines\ImageEngine;

use Symfony\Component\Stopwatch\Stopwatch;


class DefaultController extends Controller
{

    private $isUserLoggedIn = false;
    private $user;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->checkLoggedUser();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT t FROM AppBundle:Travel t ORDER BY t.id DESC');
        $travels = $query->getResult();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'user' => $this->user,
            'travels' => $travels,
        ));
    }

    /**
     * @Route("/viewtravel/{travelId}", name="viewtravel")
     */
    public function viewtravelAction($travelId)
    {




        $this->checkLoggedUser();

        $travel = $this->loadTravelById($travelId);

        $places = $travel->getPlaces();

        d($travel, $places);

        // replace this example code with whatever you need
        return $this->render('default/viewtravel.html.twig', array(
            'user' => $this->user,
            'travel' => $travel,
        ));
    }

    /**
     * @Route("/addplace/{travelId}", name="addplace")
     */
    public function addplaceAction(Request $request, $travelId)
    {
        $this->checkLoggedUser();
        $travel = $this->loadTravelById($travelId);
        $place = $this->loadOrCreatePlace($request, $travel);
        $form = $this->createForm(new PlaceType(), $place);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($travel);
            $em->persist($place);
            $travel->addPlace($place);
            $em->flush();
//            return $this->redirectToRoute('viewtravel/2');
        } else {
            d('form not validated');
        }

        return $this->render('default/addplace.html.twig', array(
            'user' => $this->user,
            'travel' => $travel,
            'place' => $place,
            'form' => $form->createView(),
        ));
    }

    private function loadTravelById($travelId)
    {
        $travel = $this->getDoctrine()
            ->getRepository('AppBundle:Travel')
            ->find($travelId)
        ;
        return $travel;
    }

    private function loadOrCreatePlace($request, $travel)
    {
        $placeId = $request->query->get('placeId');
        if($placeId !== null){
            foreach ($travel->getPlaces() as $travelPlace) {
                if($travelPlace->getId() == $placeId){
                    $place = $travelPlace;
                    break;
                }
            }
        } else {
            $place = new Place();
        }
        return $place->setTravelId($travel);
    }

    /**
     * @Route("/newtravel", name="newtravel")
     */
    public function newtravelAction(Request $request)
    {
        $this->checkLoggedUser();

		if(!$this->isUserLoggedIn){
			return $this->returnErrorPage('you must be logged in');
		}

		$travel = new Travel();
		$travel->setName('temp travel name');
		$travel->setStartDateTime(new \DateTime());
		$travel->setEndDateTime(new \DateTime());

		$form = $this->createForm(new TravelType(), $travel);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($travel);
			$em->flush();
			return $this->redirectToRoute('/viewtravel/'.$travel->getId());
		} else {
//            d('form not validated');
		}
		return $this->render('default/travelForm.html.twig', array(
			'user' => $this->user,
			'form' => $form->createView(),
			'travel' => $travel,
		));



    }
    
    /**
     * @Route("/uploadphoto", name="uploadphoto")
     */
    public function uploadphotoAction(Request $request)
    {
        $travel = $this->loadTravelById($request->query->get('travelId'));
        $place = $this->loadOrCreatePlace($request, $travel);



        $imageEngine = new ImageEngine($this->getDoctrine()->getManager());
        $targetFile = $imageEngine->uploadImage($place);

        return $this->render('default/dropzone.html.twig', array(
            'result' => $targetFile,
        ));
    }    
   
    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $ud = $this->getUploadRootDir();
        ddd($ud);
    }
    
    private function checkLoggedUser()
    {
        if ( $this->get('security.context')->isGranted('ROLE_USER')) {
            $this->isUserLoggedIn = true;
            $this->user = $this->get('security.context')->getToken()->getUser();
        }
    }

	private function returnErrorPage($errorMessage)
	{
		return $this->render('default/error.html.twig', array(
			'user' => $this->user,
			'error' => $errorMessage,
		));
	}

}
