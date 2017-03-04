<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{

    /**
     * Charger une news
     * @param Request $request
     * @param News|null $news
     * @return Response
     */
    public function chargeNewsAction(Request $request, News $news = null)
    {
        // Récupération de la dernière news
        if (true === is_null($news)) {
            $news = $this->getDoctrine()->getRepository('CommunBundle:News')->getDerniere();
        }
        $newsSuivante   = $this->getDoctrine()->getRepository('CommunBundle:News')->getSuivante($news);
        $newsPrecedente = $this->getDoctrine()->getRepository('CommunBundle:News')->getPrecedente($news);

        return $this->render('CommunBundle:News:detail.html.twig', array(
            'news'           => $news,
            'newsSuivante'   => $newsSuivante,
            'newsPrecedente' => $newsPrecedente,
        ));
    }

    /**
     * Liste toutes les actions
     * @param Request $request
     * @return Response
     */
    public function listeAction(Request $request)
    {
        // Dernier batchs lancés
        $paginator          = $this->get('knp_paginator');
        $listeNewsPaginator = $paginator->paginate(
            $this->getDoctrine()->getRepository('CommunBundle:News')->getQueryListe(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        return $this->render('CommunBundle:News:liste.html.twig', array(
            'liste_news_paginator' => $listeNewsPaginator
        ));
    }
}
