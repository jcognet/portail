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
        //TODO : affichager un lieu & gestion ajax pour afficher après modif
        //TODO : protéger sur absence de lieu (maison ? )
        //TODO : Modifier un lieu
        //TODO : ajouter un fils à un lieu
        //TODO : supprimer un lieu
        //TODO : supprimer le x à lieu quand il existe
        //TODO : créer méthode retournant le contenu html des formulaires des lieux
        //TODO : notion d'ordre sur un lieu
        $formTypeLieu = $this->createForm(LieuType::class);
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
        $typeLieu        = '';
        // On gère le form envoyé
        $formTypeLieu = $this->createForm(LieuType::class);
        $formTypeLieu->handleRequest($request);
        if ($formTypeLieu->isValid()) {
            $typeLieu        = ucfirst($formTypeLieu->get(LieuType::TYPE_LIEU_NAME)->getData());
            $formNouveauLieu = $this->createForm(
                $this->get('livre.lieu')->getFormFromTypeLieu($typeLieu)
            );
        }

        return $this->render('LivreBundle:Block:form_lieu_' . strtolower($typeLieu) . '.html.twig', array(
            'form_nouveau_lieu' => $formNouveauLieu->createView()
        ));
    }


    /**
     * Enregistre un lieu
     * @param Request $request
     * @param $typeLieu
     * @return JsonResponse
     */
    public function enregistreAjaxAction(Request $request, $typeLieu)
    {
        //TODO : protéger
        $code = '';
        $html = '';
        // Gestion du formulaore
        $formNouveauLieu = $this->createForm(
            $this->get('livre.lieu')->getFormFromTypeLieu($typeLieu)
        );
        $formNouveauLieu->handleRequest($request);
        $lieu = $formNouveauLieu->getData();
        dump($lieu);
        if ($formNouveauLieu->isValid()) {
            // Ajout de l'utilisateur
            $lieu = $formNouveauLieu->getData();
            $em   = $this->getDoctrine()->getManager();
            $lieu->setUser($this->getUser());
            $em->persist($lieu);
            $em->flush();
            $code = 200;
        } else {
            $code = 500;
        }
        $html = $this->renderView('LivreBundle:Block:form_lieu_' . strtolower($typeLieu) . '.html.twig', array(
            'form_nouveau_lieu' => $formNouveauLieu->createView()
        ));

        return new JsonResponse(array(
            'code' => $code,
            'html' => $html
        ));
    }

    /**
     * Affiche l'arbre des lieux
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficheArbreLieuAjaxAction(Request $request){

        //Source :  http://jsfiddle.net/jhfrench/GpdgF/
        return $this->render('LivreBundle:Block:form_arbre_lieu.html.twig', array(

        ));
    }
}
