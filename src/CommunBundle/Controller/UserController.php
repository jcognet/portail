<?php

namespace CommunBundle\Controller;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use UserBundle\Entity\User;

class UserController extends Controller
{

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
     * Force la connexion d'un utilisateur
     * @param Request $request
     * @param User $user
     * @return Response
     */
    private function connecteUtilisateur(Request $request, User $user)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
        $this->get("security.token_storage")->setToken($token); //now the user is logged in
        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        return $this->render('CommunBundle:Block:redirect.html.twig');
    }


    /**
     * Reconnecte automatiquement un utilisateur
     * @param Request $request
     * @param $hash
     * @param User $user
     * @return Response
     */
    public function retourAction(Request $request, $hash, User $user)
    {
        // Connexion de l'utilisateur
        if ($this->get('user.service')->verifieHachage($user, urldecode($hash))) {
            return $this->connecteUtilisateur($request, $user);
        } else {
            return new Response('Erreur');
        }
    }


}
