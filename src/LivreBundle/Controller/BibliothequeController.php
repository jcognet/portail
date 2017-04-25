<?php

namespace LivreBundle\Controller;

use LivreBundle\Form\ListeLivreType;
use LivreBundle\Form\RechercheISBNLivreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;

class BibliothequeController extends Controller
{
    public function listeAction(Request $request)
    {
        // Formulaire pour modifier les dernières entrées de la bibliotheque
        $formBibliotheque = $this->createForm(ListeLivreType::class, $this->getUser());
        // Formulaire pour ajouter un livre
        $formAjout = $this->createForm(RechercheISBNLivreType::class)
            ->add('btnAjouterLivre', ButtonType::class, array(
                'label' => 'Ajouter >>',
            ));

        return $this->render('LivreBundle:Bibliotheque:liste.html.twig', array(
            'form_bibliotheque' => $formBibliotheque->createView(),
            'form_ajout'        => $formAjout->createView()
        ));
    }

    public function ajoutAction(Request $request)
    {
        return $this->render('LivreBundle:Bibliotheque:ajout.html.twig', array(// ...
        ));
    }

    public function supprimeAction(Request $request)
    {
        return $this->render('LivreBundle:Bibliotheque:supprime.html.twig', array(// ...
        ));
    }

}
