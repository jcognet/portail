<?php

namespace LivreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BibliothequeController extends Controller
{
    public function listeAction()
    {
        return $this->render('LivreBundle:Bibliotheque:liste.html.twig', array(
            // ...
        ));
    }

    public function ajoutAction()
    {
        return $this->render('LivreBundle:Bibliotheque:ajout.html.twig', array(
            // ...
        ));
    }

    public function supprimeAction()
    {
        return $this->render('LivreBundle:Bibliotheque:supprime.html.twig', array(
            // ...
        ));
    }

}
