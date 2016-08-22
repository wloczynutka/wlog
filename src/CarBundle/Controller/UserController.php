<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use AppBundle\Entity\User;

class UserController extends Controller
{

    public function loginAction(Request $request)
    {
        return $this->render('CarBundle:Ramble:login.html.twig');
    }

    

}
