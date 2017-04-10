<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\BaseLivre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\Isbn;

class LivreController extends Controller
{
    /**
     * Liste des livres
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeAction(Request $request)
    {
        //TODO : gérer la pagination de la liste
        //TODO : gérer le détail d'un livre quand il n'y a qu'un livre
        //TODO : gérer la recherche avec plusieurs paramètres
        // Gestion des info evoyées
        $form = $this->createFormRecherche();
        // Dernier livres trouvés
        $paginator   = $this->get('knp_paginator');
        $listeLivres = $paginator->paginate(
            $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->getQueryListeLivre(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );


        return $this->render('LivreBundle:Livre:liste.html.twig', array(
            'liste_livres'   => $listeLivres,
            'form_recherche' => $form->createView()
        ));
    }


    public function editAction()
    {
        //TODO : à développer
        return $this->render('LivreBundle:Livre:edit.html.twig', array(// ...
        ));
    }

    /**
     * Affiche le détail d'un livre
     * @param Request $request
     * @param BaseLivre $livre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxDetailAction(Request $request, BaseLivre $livre)
    {
        return $this->render('LivreBundle:Block:livre_detail.html.twig', array(
            'livre' => $livre
        ));
    }

    /**
     * Affiche les résultats répondant à une requête
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ajaxRechercheAction(Request $request)
    {
        $listeLivresData = null;
        // Gestion des info envoyées
        $form = $this->createFormRecherche();
        $form->handleRequest($request);
        // Liste des erreurs du form
        $listeErreurs = array();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $isbn = $form->get('isbn')->getData();
                if (strlen($isbn) > 0) {
                    // On recherche si le livre est en base
                    $livre = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->findOneByIsbn($isbn);
                    // S'il n'existe pas, on appelle google
                    if (true === is_null($livre)) {
                        // On demande à google
                        $livre = $this->get('livre.google_get_livre_service')->rechercheLivreParISBN($isbn);
                    }
                    // On a trouvé un livre... Youhou \o/
                    if (false === is_null($livre)) {
                        $listeLivresData = array($livre);
                    }
                }
            } else {

                foreach ($form->getErrors(true, true) as $erreur) {
                    $listeErreurs[] = $erreur->getMessage();
                }
            }
        }

        if (true === is_null($listeLivresData)) {
            $listeLivresData = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->getQueryListeLivre();
        }

        // Dernier livres trouvés
        $paginator   = $this->get('knp_paginator');
        $listeLivres = $paginator->paginate(
            $listeLivresData,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('LivreBundle:Block:livre_liste.html.twig', array(
            'liste_livres'  => $listeLivres,
            'liste_erreurs' => $listeErreurs,
        ));
    }

    /**
     *  Crée le formulaire de recherche
     * @return mixed
     */
    protected function createFormRecherche()
    {
        $formBuilder = $this->createFormBuilder()
            ->add('isbn', TextType::class, array(
                'required' => false,
                'label'    => 'N° ISBN : ',

                'constraints' => array(new Isbn(array(
                    'bothIsbnMessage' => "{{ value }} n'est pas un ISBN 10 ou 13."
                )))
            ))
            ->add('chercher', ButtonType::class, array(
                'label' => 'Chercher',

            ));

        return $formBuilder->getForm();
    }

}
