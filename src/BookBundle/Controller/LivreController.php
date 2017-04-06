<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LivreController extends Controller
{
    public function listeAction(Request $request)
    {
        // Dernier batchs lancés
        $paginator   = $this->get('knp_paginator');
        $listeLivres = $paginator->paginate(
            $this->getDoctrine()->getRepository('BookBundle:BaseLivre')->getQueryListeLivre(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('BookBundle:Livre:liste.html.twig', array(
            'liste_livres' => $listeLivres
        ));
    }

    public function editAction()
    {
        return $this->render('BookBundle:Livre:edit.html.twig', array(// ...
        ));
    }
    

}
