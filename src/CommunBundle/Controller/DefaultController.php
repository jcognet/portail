<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\Devise;
use CommunBundle\Entity\News;
use CommunBundle\Entity\SuiviDevise;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use UserBundle\Entity\User;

class DefaultController extends Controller
{

    /**
     * Page d'accueil
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Liste des devises
        $listeDevise        = $this->getDoctrine()->getRepository('DeviseBundle:Devise')->getListe();
        $listeDeviseSuivies = array();
        $user               = $this->getUser();
        if ($user instanceof User) {
            $listeDeviseSuivies = $user->getListeDevises();
        }
        return $this->render('CommunBundle:Default:index.html.twig',
            array(
                'liste_devise'          => $listeDevise,
                'liste_devises_suivies' => $listeDeviseSuivies
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
            ->add('save', SubmitType::class, array('label' => 'Envoyer le mail'))
            ->getForm();
        // Gestion du post
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $retour    = $request->request->get('g-recaptcha-response');
            $recaptcha = new ReCaptcha($this->getParameter('recaptcha_secret_key'));
            $resp      = $recaptcha->verify($retour, $request->getClientIp());
            if (strlen($retour) == 0) {
                $this->addFlash(
                    'danger',
                    'Le captcha a rencontré une erreur. Merci de recommencer.'
                );
            } elseif (!$resp->isSuccess()) {
                $this->addFlash(
                    'danger',
                    'Le captcha a rencontré une erreur : ' . $resp->error . '. Merci de recommencer.'
                );
            } else {
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

        }


        return $this->render('CommunBundle:Default:contact.html.twig', array(
            'form' => $form->createView()));
    }

}
