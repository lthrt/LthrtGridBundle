<?php

namespace Lthrt\GridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LthrtGridBundle:Default:index.html.twig', array('name' => $name));
    }
}
