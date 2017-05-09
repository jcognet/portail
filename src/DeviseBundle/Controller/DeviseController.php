<?php

namespace DeviseBundle\Controller;

use DeviseBundle\Entity\Devise;
use DeviseBundle\Entity\SuiviDevise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DeviseController extends Controller
{


    /**
     * Récupèr le cours d'une devise
     * @param Request $request
     * @param Devise $devise
     * @return JsonResponse
     */
    public function getCoursAjaxAction(Request $request, Devise $devise)
    {
        $data = array(
            'label'       => $devise->getLabel(),
            'symbole'     => $devise->getRaccourciOuLabel(),
            'cours'       => $devise->getCoursJour(),
            'moyenne_30'  => $devise->getMoyenne30Jours(),
            'moyenne_60'  => $devise->getMoyenne60Jours(),
            'moyenne_90'  => $devise->getMoyenne90Jours(),
            'moyenne_120' => $devise->getMoyenne120Jours(),
        );

        $listeCoursJournee = $this->getDoctrine()->getRepository('DeviseBundle:CoursJournee')->getListeSurPeriode(null, $this->getParameter('nombre_jours'), $devise);
        foreach ($listeCoursJournee as $coursJournees) {
            $data['cours'][] = array(
                'taux' => $coursJournees->getCours(),
                'date' => $coursJournees->getDate()->format('d/m/Y'),
            );
        }

        // Envoie de la réponse
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    /**
     * Afficher le bloc des cours
     * @param Request $request
     * @param Devise $devise
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderCoursAjaxAction(Request $request, Devise $devise)
    {
        $data = array(
            'label'       => $devise->getLabel(),
            'symbole'     => $devise->getRaccourciOuLabel(),
            'cours'       => $devise->getCoursJour(),
            'moyenne_30'  => $devise->getMoyenne30Jours(),
            'moyenne_60'  => $devise->getMoyenne60Jours(),
            'moyenne_90'  => $devise->getMoyenne90Jours(),
            'moyenne_120' => $devise->getMoyenne120Jours(),
        );

        $listeCoursJournee = $this->getDoctrine()->getRepository('DeviseBundle:CoursJournee')->getListeSurPeriode(null, $this->getParameter('nombre_jours'), $devise);
        foreach ($listeCoursJournee as $coursJournees) {
            $data['cours'][] = array(
                'taux' => $coursJournees->getCours(),
                'date' => $coursJournees->getDate()->format('d/m/Y'),
            );
        }

        $user        = $this->getUser();
        $suiviDevise = null;
        if (!is_null($user)) {
            $suiviDevise = $this->getDoctrine()->getManager()->getRepository('DeviseBundle:SuiviDevise')->findOneBy(
                array(
                    'user'   => $user,
                    'devise' => $devise
                ));
        }

        // Envoie de la réponse
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $this->render('CommunBundle:Block:devise.html.twig',
            array(
                'devise'      => $devise,
                'divId'       => 'chart',
                'suiviDevise' => $suiviDevise,
                'json'        => $jsonResponse->getContent()
            )
        );
    }

    /**
     * Calcule le change d'une devise / euro en Ajax
     * @param Request $request
     * @param Devise $devise
     * @param $valeurEuros
     * @param $valeurAutre
     * @return JsonResponse
     */
    public function calculDeviseAjaxAction(Request $request, Devise $devise, $valeurEuros, $valeurAutre)
    {
        //TODO : protéger sur les valeurs $valeurEuros et $valeurAutre, que se passe-t-il si chaîne de caractères ou float ?
        // Calcul
        if ($valeurEuros > 0) {
            $data = $valeurEuros * $devise->getCoursJour();
        } else {
            $data = $valeurAutre / $devise->getCoursJour();
        }
        // Retour
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($this->get('devise.devise_extension')->affichePrix($data));
        return $jsonResponse;
    }

    /**
     * Sauve le cours d'une devise en AJAX pour un utilisateur
     * @param Request $request
     * @param Devise $devise
     * @param $seuilMax
     * @param $seuil
     * @return JsonResponse
     */
    public function sauveDeviseAjaxAction(Request $request, Devise $devise, $seuilMax, $seuil)
    {
        // Initilisation des variables
        $jsonResponse = new JsonResponse();
        $user         = $this->getUser();
        $seuilMax     = ($seuilMax == 'true') ? true : false;
        $seuil        = str_replace(',', '.', $seuil);
        $seuil        = floatval($seuil);
        // Protection de la page
        if (!$user instanceof User) {
            $valeurRetour = false;
            $jsonResponse->setData(array('success' => $valeurRetour));
            return $jsonResponse;
        }
        // On enregistre en base
        $em = $this->getDoctrine()->getManager();
        if (is_null($suiviDevise = $em->getRepository('DeviseBundle:SuiviDevise')->findOneBy(
            array(
                'user'   => $user,
                'devise' => $devise
            )
        ))) {
            $suiviDevise = new SuiviDevise();
            $suiviDevise->setUser($user)
                ->setDevise($devise);
        }
        if ($seuilMax) {
            $suiviDevise->setSeuilMax($seuil);
        } else {
            $suiviDevise->setSeuilMin($seuil);
        }
        $em->persist($suiviDevise);
        $em->flush();

        $valeurRetour = true;
        $jsonResponse->setData(array('success' => $valeurRetour));


        return $jsonResponse;
    }

}
