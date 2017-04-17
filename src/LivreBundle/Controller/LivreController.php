<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\BaseLivre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\Isbn;

class LivreController extends Controller
{
    /**
     * Nombre de livres dans la pagination
     */
    const MAX_ELEMENT_PAGINATION = 10;

    /**
     * Liste des livres
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeAction(Request $request)
    {
        // TODO : pop up détail livre image
        // Gestion des info evoyées
        $form = $this->createFormRecherche();
        // Dernier livres trouvés
        $paginator   = $this->get('knp_paginator');
        $listeLivres = $paginator->paginate(
            $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->getQueryListeLivre(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', self::MAX_ELEMENT_PAGINATION)
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
    public function detailAjaxAction(Request $request, BaseLivre $livre)
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
    public function rechercheAjaxAction(Request $request)
    {
        $listeLivresData = null;
        // Gestion des info envoyées
        $form = $this->createFormRecherche();
        $form->handleRequest($request);
        // Liste des erreurs du form
        $listeErreurs = array();
        dump($form);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $isbn = $form->get('isbn')->getData();
                // Si on a un isbn : on tente de chercher sur ce champ
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
                else{
                    $texte = $form->get('label')->getData();
                    // Sinon on tente un autre champ
                    $listeLivresData = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')
                        ->getQueryLivreLivreRecherche($texte);
                }
            } else {
                // Affichage de tous les erreurs
                foreach ($form->getErrors(true, true) as $erreur) {
                    $listeErreurs[] = $erreur->getMessage();
                }
            }
        }
        // Par défaut : toute la liste
        if (true === is_null($listeLivresData)) {
            $listeLivresData = $this->getDoctrine()->getRepository('LivreBundle:BaseLivre')->getQueryListeLivre();
        }

        // Dernier livres trouvés
        $paginator   = $this->get('knp_paginator');
        $listeLivres = $paginator->paginate(
            $listeLivresData,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', self::MAX_ELEMENT_PAGINATION)
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
            ->add('label', TextType::class, array(
                'required' => false,
                'label'    => 'Texte',

            ))
            ->add('chercher', SubmitType::class, array(
                'label' => 'Chercher',

            ));

        return $formBuilder->getForm();
    }

}
