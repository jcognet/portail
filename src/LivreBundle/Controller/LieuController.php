<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\Maison;
use LivreBundle\Form\Type\LieuType;
use LivreBundle\Form\Type\MaisonType;
use LivreBundle\Interfaces\LieuInterface;
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
        $this->securite();
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
        $this->securite();
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
     * Retourne le formulaire lié à un lieu
     * @param Request $request
     * @param $typeLieu
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFormUpdateAjaxAction(Request $request, $typeLieu, $id = null)
    {
        $formNouveauLieu = null;
        $typeLieu        = ucfirst($typeLieu);
        $lieu            = $this->get('livre.lieu')->getEntityFromTypeLieu($typeLieu, $id);
        $this->securite($lieu);
        $formNouveauLieu = $this->createForm(
            $this->get('livre.lieu')->getFormFromTypeLieu($typeLieu), $lieu
        );

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
    public function enregistreAjaxAction(Request $request, $typeLieu, $id = null)
    {
        $code = '';
        $html = '';
        $lieu = null;
        // Récupération en base si existant
        if (false === is_null($id) && strlen($id) > 0 && intval($id) > 0)
            $lieu = $this->get('livre.lieu')->getEntityFromTypeLieu($typeLieu, $id);
        $this->securite($lieu);
        // Gestion du formulaire
        $formNouveauLieu = $this->createForm(
            $this->get('livre.lieu')->getFormFromTypeLieu($typeLieu), $lieu
        );
        $formNouveauLieu->handleRequest($request);
        // Si aucun lieu, on récupère la data
        if (true === is_null($lieu))
            $lieu = $formNouveauLieu->getData();
        if ($formNouveauLieu->isValid()) {
            // Ajout de l'utilisateur
            $em = $this->getDoctrine()->getManager();
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
     * Supprimer un lieu
     * @param Request $request
     * @param $typeLieu
     * @return JsonResponse
     */
    public function supprimeAjaxAction(Request $request, $typeLieu, $id)
    {
        $lieu = $this->get('livre.lieu')->getEntityFromTypeLieu($typeLieu, $id);
        $this->securite($lieu);
        $em = $this->getDoctrine()->getManager();
        $em->remove($lieu);
        $em->flush();
        $code = 200;

        return new JsonResponse(array(
            'code' => 200,
        ));
    }

    /**
     * Affiche l'arbre des lieux
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficheArbreLieuAjaxAction(Request $request)
    {
        $this->securite();
        //Source :  http://jsfiddle.net/jhfrench/GpdgF/
        return $this->render('LivreBundle:Block:form_arbre_lieu.html.twig', array());
    }

    /**
     * Gère la sécurité du controller
     * @param LieuInterface $lieu
     * @return bool
     */
    protected function securite(LieuInterface $lieu = null)
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        // Vérifie si l'utilisateur possède bien le lieu en paramètre
        $user = $this->getUser();
        if (false === is_null($lieu) && $lieu->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }
        return true;
    }


    /**
     *  Ajoute un fils à un type de lieu avec un id donné
     * @param Request $request
     * @param $typeLieu
     * @param $id
     * @return JsonResponse
     */
    public function ajouteFilsAjaxAction(Request $request, $typeLieu, $id)
    {
        // Initilisation
        $formNouveauLieu = null;
        $typeLieu        = ucfirst($typeLieu);
        $lieu            = $this->get('livre.lieu')->getEntityFromTypeLieu($typeLieu, $id);
        $typeLieuFils    = $this->get('livre.lieu')->getTypeLieuForFils($typeLieu);
        // Sécurite
        $this->securite($lieu);
        // Gestion du formulaore
        $formNouveauLieu = $this->createForm(
            $this->get('livre.lieu')->getFormFromTypeLieu($typeLieuFils)
        );
        $formNouveauLieu->get(strtolower($typeLieu))->setData($lieu);
        // Html
        $html = $this->renderView('LivreBundle:Block:form_lieu_' . strtolower($typeLieuFils) . '.html.twig', array(
            'form_nouveau_lieu' => $formNouveauLieu->createView()
        ));
        // Retourne
        return new JsonResponse(array(
                'html'         => $html,
                'typeLieuFils' => $typeLieuFils
            )
        );

    }
}
