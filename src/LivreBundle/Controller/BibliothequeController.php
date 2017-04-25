<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\Livre;
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
        $formAjout = $this->createForm(RechercheISBNLivreType::class);

        $formAjout->handleRequest($request);
        $listeErreurs = array();
        if ($formAjout->isValid()) {
            $isbn = $formAjout->get('isbn')->getData();
            // On recherche si le livre est en base
            $baseLivre = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->findOneByIsbn($isbn);
            // S'il n'existe pas, on appelle google
            if (true === is_null($baseLivre)) {
                // On demande à google
                $baseLivre = $this->get('livre.google_get_livre_service')->rechercheLivreParISBN($isbn);
            }
            // On a trouvé un livre... Youhou \o/
            if (false === is_null($baseLivre)) {
                $livre = new Livre();
                $livre->setAction('ajout')
                    ->setDateAction(new \DateTime())
                    ->setDateAjout(new \DateTime())
                    ->setPrix($baseLivre->getPrix())
                    ->setProprietaire($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($livre);
                $em->flush();
            } else {
                $listeErreurs[] = "Le livre avec l'ISBN " . $isbn . " n'a psa été trouvé par google. ";
            }

        } else {
            // Affichage de tous les erreurs
            foreach ($formAjout->getErrors(true, true) as $erreur) {
                $listeErreurs[] = $erreur->getMessage();
            }
        }

        return $this->render('LivreBundle:Block:bibliotheque_ajout_livre.html.twig', array(
            'liste_erreurs' => $listeErreurs
        ));
    }

    public function supprimeAction(Request $request)
    {
        return $this->render('LivreBundle:Bibliotheque:supprime.html.twig', array(// ...
        ));
    }

}
