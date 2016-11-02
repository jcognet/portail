<?php

namespace CommunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommunBundle:Default:index.html.twig');
    }

    public function contactAction()
    {
        // Création du formulaire
        $data        = array();
        $formBuilder = $this->createFormBuilder($data)
            ->
            add('email', EmailType::class, array(
                'label'       => 'contact.email',
                'constraints' => array(
                    New NotBlank(array()),
                    New Email(array())
                )
            ))
            ->add('sujet', TextType::class)
            ->add('corps', TextareaType::class)
            ->add('save', SubmitType::class);
        $form        = $formBuilder->getForm();
        // Gestion du post
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->get('site.mailer')->envoiEmail(
                'CommunBundle:Email:contact.html.twig',
                $context,
                $this->getParameter('destinataire_mail')
            );
            $this->addFlash(
                'notice',
                'Le email est bien parti'
            );
        }


        return $this->render('CommunBundle:Default:contact.html.twig');
    }
}
