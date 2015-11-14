<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AppBundle\Entity\Travel;
use \AppBundle\Form\TravelType;

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

        d($travels, $this->user);


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
        $travel = $this->getDoctrine()
            ->getRepository('AppBundle:Travel')
            ->find($travelId);


        d($travel);


        // replace this example code with whatever you need
        return $this->render('default/viewtravel.html.twig', array(
            'user' => $this->user,
            'travel' => $travel,
        ));
    }

    /**
     * @Route("/newplace", name="newplace")
     */
    public function newplaceAction(Request $request)
    {
        $this->checkLoggedUser();
        $travel = new Travel();
        $travel->setName('temp travel name');
        $travel->setStartDateTime(new \DateTime());
        $travel->setEndDateTime(new \DateTime());

        $form = $this->createForm(new TravelType(), $travel);


//        var_dump($form, $request);
        $form->handleRequest($request);




        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($travel);
            $em->flush();
//            return $this->redirectToRoute('task_success');
        } else {
            d('form not validated');
        }


        return $this->render('default/travelForm.html.twig', array(
            'user' => $this->user,
            'form' => $form->createView(),
        ));

        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', array(
//            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
//        ));
    }

    private function checkLoggedUser()
    {
        if ( $this->get('security.context')->isGranted('ROLE_USER')) {
            $this->isUserLoggedIn = true;
            $this->user = $this->get('security.context')->getToken()->getUser();
        }
    }

}
