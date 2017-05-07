<?php

namespace LivreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommunController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuAction(Request $request)
    {
        $routeCourante = $this->get('request_stack')->getMasterRequest()->get('_route');
        return $this->render('LivreBundle:Commun:menu.html.twig', array(
            'route_courante'=>$routeCourante
        ));
    }

}
