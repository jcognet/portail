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
        // Gestion des info evoyées
        $form = $this->createFormRecherche();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            die('ok');
        }
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
        return $this->render('LivreBundle:Block:detail.html.twig', array(
            'livre' => $livre
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
                'constraints'  => array(new Isbn(array(
                    'bothIsbnMessage' => "Cette valeur n'est pas un ISBN 10 ou 13."
                )))
            ))
            ->add('chercher', ButtonType::class, array(
                'label' => 'Chercher',

            ));
        return $formBuilder->getForm();
    }

}
