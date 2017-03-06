<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\News;
use CommunBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        // Dernières news affichées
        $paginator          = $this->get('knp_paginator');
        $listeNewsPaginator = $paginator->paginate(
            $this->getDoctrine()->getRepository('CommunBundle:News')->getQueryListe(
                $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            ),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        return $this->render('CommunBundle:News:liste.html.twig', array(
            'liste_news_paginator' => $listeNewsPaginator
        ));
    }


    /**
     * Enregistre une news
     * @param Request $request
     * @param News|null $news
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function enregistreNewsAction(Request $request, News $news = null)
    {
        // Protection
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible de se conecter à cette page.');

        if(true === is_null($news))
            $news = new News();
        // Création du formulaire
        $form = $this->createForm(NewsType::class, $news)
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
        ;
        // Gestion du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($news);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'notice',
                "La news a bien été mise à jour."
            );
            return $this->redirectToRoute('commun_news_liste');
        }

        return $this->render('CommunBundle:News:enregistre.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Supprimer une action
     * @param Request $request
     * @param News $news
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteNewsAction(Request $request, News $news){
        // Protection
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Impossible de se conecter à cette page.');

        $this->getDoctrine()->getManager()->remove($news);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash(
            'notice',
            "La news a bien été supprimée."
        );
        return $this->redirectToRoute('commun_news_liste');
    }
}
