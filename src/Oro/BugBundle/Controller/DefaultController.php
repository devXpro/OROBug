<?php

namespace Oro\BugBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OroBugBundle:Default:index.html.twig', array('name' => $name));
    }
}
