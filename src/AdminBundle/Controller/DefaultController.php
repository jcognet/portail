<?php

namespace AdminBundle\Controller;

use TransverseBundle\Entity\Batch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * page d'accueil de la partie admin
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Batch en cours
        $listeBatchEnCours = $this->getDoctrine()->getRepository('TransverseBundle:Batch')->getBatchEnCours();

        // Dernier batch lancé
        $dernierBatchDevise = $this->getDoctrine()->getRepository('TransverseBundle:Batch')->getDernierBatch(Batch::TYPE_IMPORT_DEVISE);
        $dernierBatchAlerte = $this->getDoctrine()->getRepository('TransverseBundle:Batch')->getDernierBatch(Batch::TYPE_ALERTE_ENVOYEE);
        // Nombre d'utilisateur
        $nbUserActif = $this->getDoctrine()->getRepository('UserBundle:User')->getNombreUserActifs();

        // Dernier batchs lancés
        $paginator       = $this->get('knp_paginator');
        $batchPagination = $paginator->paginate(
            $this->getDoctrine()->getRepository('TransverseBundle:Batch')->getQueryListBatch(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        // News dans le futur
        $liste_news_futur = $this->getDoctrine()->getRepository('CommunBundle:News')->getListeNewsFutur();
        return $this->render('AdminBundle:Default:index.html.twig',
            array(
                'dernier_batch_devise' => $dernierBatchDevise,
                'dernier_batch_alerte' => $dernierBatchAlerte,
                'batch_pagination'     => $batchPagination,
                'liste_batch_en_cours' => $listeBatchEnCours,
                'nb_user_actifs'       => $nbUserActif,
                'liste_news_futur'     => $liste_news_futur
            ));
    }
}
