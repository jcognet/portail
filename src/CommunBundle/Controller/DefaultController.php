<?php

namespace CommunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommunBundle:Default:index.html.twig');
    }
}
