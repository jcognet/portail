<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\Type\ConfigurationAlerteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CustomProfileController extends Controller
{

    /**
     * Gère les arletes de l'utilisateur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function configurationAlerteAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ConfigurationAlerteType::class, $user)
            ->add('btnSauve', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'Votre profil a bien été enregistré.');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('UserBundle:CustomProfile:configuration_alerte.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
