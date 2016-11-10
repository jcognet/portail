<?php

namespace CommunBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\ClearQueryCacheDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // Récupération des news
        $listeNews = $this->getDoctrine()->getRepository('CommunBundle:News')->getListe($this->getParameter('nombre_news'));
        // Liste des devises
        $listeDevise = $this->getDoctrine()->getRepository('CommunBundle:Devise')->getListe();
        return $this->render('CommunBundle:Default:index.html.twig',
            array(
                'liste_news' => $listeNews
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
}
