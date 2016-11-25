<?php

namespace AdminBundle\Controller;

use CommunBundle\Entity\Batch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $paginator = $this->get('knp_paginator');

        // Dernier batchs lancés
        $batchPagination = $paginator->paginate(
            $this->getDoctrine()->getRepository('CommunBundle:Batch')->getQueryListBatch(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        return $this->render('AdminBundle:Default:index.html.twig',
            array(
                'batch_pagination' => $batchPagination
            ));
    }
}
