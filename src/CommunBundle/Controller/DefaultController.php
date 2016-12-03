<?php

namespace CommunBundle\Controller;

use CommunBundle\Entity\Devise;
use CommunBundle\Entity\SuiviDevise;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\ClearQueryCacheDoctrineCommand;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        // Récupération des news
        $listeNews = $this->getDoctrine()->getRepository('CommunBundle:News')->getListe($this->getParameter('nombre_news'));
        // Liste des devises
        $listeDevise        = $this->getDoctrine()->getRepository('CommunBundle:Devise')->getListe();
        $listeDeviseSuivies = array();
        $user               = $this->getUser();
        if ($user instanceof User) {
            $listeDeviseSuivies = $user->getListeDevises();
        }
        return $this->render('CommunBundle:Default:index.html.twig',
            array(
                'liste_news'            => $listeNews,
                'liste_devise'          => $listeDevise,
                'liste_devises_suivies' => $listeDeviseSuivies
            )
        );
    }

    /**
     * Affiche le formulaire et de login
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerOuLoginAction(Request $request)
    {
        // Gestion d'une mauvaise authentification
        $authenticationUtils = $this->get('security.authentication_utils');
        $error               = $authenticationUtils->getLastAuthenticationError();
        $lastUsername        = $authenticationUtils->getLastUsername();

        // Gestion d'un formulaire d'enregistrement
        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->createUser();
        $user->setEnabled(true);
        $formEnregistrement = $this->createForm(RegistrationFormType::class)
            ->add('btnSauver', SubmitType::class, array(
                'label' => 'Créer compte'
            ))
            ->remove('username');
        $formEnregistrement->setData($user);
        $formEnregistrement->handleRequest($request);
        if ($formEnregistrement->isSubmitted()) {
            if ($formEnregistrement->isValid()) {
                $user->setUsername($user->getEmail());
                $userManager->updateUser($user);
                $this->addFlash(
                    'notice',
                    'L\'utilisateur a bien été créé.'
                );

                return $this->connecteUtilisateur($request, $user);
            }
        }


        return $this->render('CommunBundle:Block:login.html.twig',
            array(
                'form_enregistement' => $formEnregistrement->createView(),
                'error'              => $error,
                'lastUsername'       => $lastUsername
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

            $recaptcha = new ReCaptcha($this->getParameter('recaptcha_secret_key'));
            $resp      = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

            if (!$resp->isSuccess()) {
                $this->addFlash(
                    'danger',
                    'Le captcha a rencontré une erreur : ' . $resp->error . '. Merci de recommencer.'
                );
            } else {
                $data = $form->getData();
                $data['corps'] = nl2br($data['corps']);
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

    /**
     * Récupèr le cours d'une devise
     * @param Request $request
     * @param Devise $devise
     * @return JsonResponse
     */
    public
    function getCoursAjaxAction(Request $request, Devise $devise)
    {
        $data = array(
            'label'       => $devise->getLabel(),
            'symbole'     => $devise->getRaccourciOuLabel(),
            'cours'       => $devise->getCoursJour(),
            'moyenne_30'  => $devise->getMoyenne30Jours(),
            'moyenne_60'  => $devise->getMoyenne60Jours(),
            'moyenne_90'  => $devise->getMoyenne90Jours(),
            'moyenne_120' => $devise->getMoyenne120Jours(),
            'cours'       => array()
        );

        $listeCoursJournee = $this->getDoctrine()->getRepository('CommunBundle:CoursJournee')->getListeSurPeriode(null, $this->getParameter('nombre_jours'), $devise);
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
    public
    function renderCoursAjaxAction(Request $request, Devise $devise)
    {
        $data = array(
            'label'       => $devise->getLabel(),
            'symbole'     => $devise->getRaccourciOuLabel(),
            'cours'       => $devise->getCoursJour(),
            'moyenne_30'  => $devise->getMoyenne30Jours(),
            'moyenne_60'  => $devise->getMoyenne60Jours(),
            'moyenne_90'  => $devise->getMoyenne90Jours(),
            'moyenne_120' => $devise->getMoyenne120Jours(),
            'cours'       => array()
        );

        $listeCoursJournee = $this->getDoctrine()->getRepository('CommunBundle:CoursJournee')->getListeSurPeriode(null, $this->getParameter('nombre_jours'), $devise);
        foreach ($listeCoursJournee as $coursJournees) {
            $data['cours'][] = array(
                'taux' => $coursJournees->getCours(),
                'date' => $coursJournees->getDate()->format('d/m/Y'),
            );
        }

        $user        = $this->getUser();
        $suiviDevise = null;
        if (!is_null($user)) {
            $suiviDevise = $this->getDoctrine()->getManager()->getRepository('CommunBundle:SuiviDevise')->findOneBy(
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
    public
    function calculDeviseAjaxAction(Request $request, Devise $devise, $valeurEuros, $valeurAutre)
    {
        //TODO : protéger sur les valeurs $valeurEuros et $valeurAutre, que se passe-t-il si chaîne de caractères ou float ?
        // Calcul
        $data = 0;
        if ($valeurEuros > 0) {
            $data = $valeurEuros * $devise->getCoursJour();
        } else {
            $data = $valeurAutre / $devise->getCoursJour();
        }
        // Retour
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($this->get('commun.devise_extension')->affichePrix($data));
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
    public
    function sauveDeviseAjaxAction(Request $request, Devise $devise, $seuilMax, $seuil)
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
        if (is_null($suiviDevise = $em->getRepository('CommunBundle:SuiviDevise')->findOneBy(
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

    /**
     * Reconnecte automatiquement un utilisateur
     * @param Request $request
     * @param $hash
     * @param User $user
     * @return Response
     */
    public
    function retourAction(Request $request, $hash, User $user)
    {
        // Connexion de l'utilisateur
        if ($this->get('user.service')->verifieHachage($user, urldecode($hash))) {
            return $this->connecteUtilisateur($request, $user);
        } else {
            return new Response('Erreur');
        }
    }

    /**
     * Force la connexion d'un utilisateur
     * @param Request $request
     * @param User $user
     * @return Response
     */
    private
    function connecteUtilisateur(Request $request, User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get("security.token_storage")->setToken($token); //now the user is logged in
        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        return $this->render('CommunBundle:Block:redirect.html.twig');
    }
}
