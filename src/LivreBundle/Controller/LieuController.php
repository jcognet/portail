<?php

namespace LivreBundle\Controller;

use LivreBundle\Entity\Maison;
use LivreBundle\Form\MaisonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        // Affichage
        return $this->render('LivreBundle:Lieu:liste.html.twig', array());
    }

    public function getFormMaisonAction(Request $request, $type)
    {
        //TODO : protéger
        //TODO : rendre dynamique
        $formMaison = $this->createForm(MaisonType::class)
            ->add('btnSave', ButtonType::class, array(
                'label' => 'Ajouter une maison'
            ));
        return $this->render('LivreBundle:Block:form_maison.html.twig', array(
            'form_maison' => $formMaison
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
        $html = $this->render('LivreBundle:Block:form_maison.html.twig', array(
            'form_maison' => $formMaison
        ));
        return new JsonResponse(array(
            'code' => $code,
            'html' => $html
        ))
    }


}
