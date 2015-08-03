<?php

namespace X\InventoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('InventoryBundle:Default:index.html.twig', array('name' => $name));
    }
}
