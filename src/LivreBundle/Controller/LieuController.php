<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\Maison;
use LivreBundle\Form\LieuType;
use LivreBundle\Form\MaisonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LieuController extends Controller
{
    /**
     * Liste des lieux
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeAction(Request $request)
    {
        //TODO : protéger
        //TODO : liaison avec utilisateur (pour toutes les entités)
        $formTypeLieu = $this->createForm(LieuType::class)
            ->add('btnChoix', ButtonType::class, array(
                'label' => '+'
            ));
        // Affichage
        return $this->render('LivreBundle:Lieu:liste.html.twig', array(
            'form_type_lieu' => $formTypeLieu->createView()
        ));
    }

    /**
     * Créer le formulaire d'un type de lieu
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFormAjaxAction(Request $request)
    {
        // Ce type de formulaire est dynamique
        $formNouveauLieu = null;
        $typeLieux       = '';
        // On gère le form envoyé
        $formTypeLieu = $this->createForm(LieuType::class)->add('btnChoix', ButtonType::class, array(
            'label' => '+'
        ));
        $formTypeLieu->handleRequest($request);
        if ($formTypeLieu->isValid()) {
            $typeLieux       = ucfirst($formTypeLieu->get(LieuType::TYPE_LIEU_NAME)->getData());
            $formNouveauLieu = $this->createForm(
                $this->get('livre.lieu')->getFormFromTypeLieu($typeLieux)
            );
        }

        return $this->render('LivreBundle:Block:form_lieu_' . strtolower($typeLieux) . '.html.twig', array(
            'form_nouveau_lieu' => $formNouveauLieu->createView()
        ));
    }


    public function enregistreMaisonAjaxAction(Request $request)
    {
        //TODO : protéger
        //TODO : rendre généraliste
        $maison     = new Maison();
        $formMaison = $this->createForm(MaisonType::class, $maison)
            ->add('btnSave', ButtonType::class, array(
                'label' => 'Ajouter une maison'
            ));
        $formMaison->handleRequest($request);
        if ($formMaison->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($maison);
            $em->flush();
            $code = 200;
            // Nettoyage en cas de succes
            $formMaison = $this->createForm(MaisonType::class)
                ->add('btnSave', ButtonType::class, array(
                    'label' => 'Ajouter une maison'
                ));
        } else {
            $code = 500;
        }
        $html = $this->renderView('LivreBundle:Block:form_maison.html.twig', array(
            'form_maison' => $formMaison
        ));
        return new JsonResponse(array(
            'code' => $code,
            'html' => $html
        ));
    }


}
