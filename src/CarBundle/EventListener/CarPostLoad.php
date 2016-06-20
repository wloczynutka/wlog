<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CarBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use CarBundle\Entity\Car;

/**
 * Description of CarPostLoad
 *
 * @author Łza
 */
class CarPostLoad
{
    public function postLoad(LifecycleEventArgs $args)
       {
           $entity = $args->getEntity();

           // only act on some "Product" entity
           if (!$entity instanceof Car) {
               return;
           }
           $entity->__postLoad();
       }
}
