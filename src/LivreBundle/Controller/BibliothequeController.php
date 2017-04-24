<?php

namespace LivreBundle\Controller;

use LivreBundle\Form\ListeLivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BibliothequeController extends Controller
{
    public function listeAction(Request $request)
    {
        $form = $this->createForm(ListeLivreType::class, $this->getUser());

        return $this->render('LivreBundle:Bibliotheque:liste.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    public function ajoutAction(Request $request)
    {
        return $this->render('LivreBundle:Bibliotheque:ajout.html.twig', array(
            // ...
        ));
    }

    public function supprimeAction(Request $request)
    {
        return $this->render('LivreBundle:Bibliotheque:supprime.html.twig', array(
            // ...
        ));
    }

}
