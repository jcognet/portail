<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\Devise;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\ClearQueryCacheDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{

    /**
     * Page d'accueil
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Récupération des news
        $listeNews = $this->getDoctrine()->getRepository('CommunBundle:News')->getListe($this->getParameter('nombre_news'));
        // Liste des devises
        $listeDevise = $this->getDoctrine()->getRepository('CommunBundle:Devise')->getListe();
        return $this->render('CommunBundle:Default:index.html.twig',
            array(
                'liste_news'   => $listeNews,
                'liste_devise' => $listeDevise
            )
        );
    }

    /**
     * Page de contact
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        // Création du formulaire
        $data = array();
        $form = $this->createFormBuilder($data)
            ->
            add('email', EmailType::class, array(
                'constraints' => array(
                    New NotBlank(array()),
                    New Email(array())
                )
            ))
            ->add('sujet', TextType::class)
            ->add('corps', TextareaType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        // Gestion du post
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->get('commun.mailer')->envoieEmail(
                'CommunBundle:Email:contact.html.twig',
                $data,
                $this->getParameter('destinataire_mail')
            );
            $this->addFlash(
                'notice',
                'Le email est bien parti'
            );
        }


        return $this->render('CommunBundle:Default:contact.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * Récupèr le cours d'une devise
     * @param Request $request
     * @param Devise $devise
     * @return JsonResponse
     */
    public function getCoursAjaxAction(Request $request, Devise $devise)
    {
        $data        = array();
        $listeCoursJournee = $this->getDoctrine()->getRepository('CommunBundle:CoursJournee')->getListeSurPeriode(null, $this->getParameter('nombre_jours'), $devise);
        foreach ($listeCoursJournee as $coursJournees) {
            $data[] = array(
                'cours' => $coursJournees->getCours(),
                'date'  => $coursJournees->getDate()->format('d/m/Y'),
            );
        }

        // Envoie de la réponse
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }
}
