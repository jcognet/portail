<?php

namespace CommunBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\User;
use UserBundle\Service\UserService;

/**
 * Service de mailer lié à l'application
 *
 * Class MailerService
 * @package  CommunBundle\Service
 */
class MailerService
{
    /**
     * service de templating
     * @var null|\Twig_Environment
     */
    private $twig = null;

    /**
     * Expéditeur du mail
     * @var string
     */
    private $mailer_from = null;

    /**
     * Service Swiftmailer
     * @var \Swift_Mailer
     */
    private $mailer = null;

    /**
     * Router Symfony
     * @var null|Router
     */
    private $router = null;


    /**
     * @var null|UserService
     */
    private $userService = null;

    /**
     * MailerService constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     * @param $mailer_from
     * @param Router $router
     * @param UserService $userService
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, $mailer_from, Router $router, UserService $userService)
    {
        $this->mailer      = $mailer;
        $this->twig        = $twig;
        $this->mailer_from = $mailer_from;
        $this->router      = $router;
        $this->userService = $userService;
    }

    /**
     * Envoi un email
     * @param $template
     * @param $var
     * @param $destinataire
     * @param array $fichiers
     * @return int
     */
    public function envoieEmail($template, $var, $destinataire, $fichiers = [])
    {
        // Gestion du destinataire
        $emailDestinaire = $destinataire;
        if ($destinataire instanceof User) {
            $emailDestinaire   = $destinataire->getEmail();
            $var['url_retour'] = $this->router->generate('commun_reconnexion', array(
                'id'   => $destinataire->getId(),
                'hash' => urlencode($this->userService->calculeHachage($destinataire))
            ), UrlGeneratorInterface::ABSOLUTE_URL);
        }

        // Création des variables pour twig
        $context  = $this->twig->mergeGlobals($var);
        $template = $this->twig->loadTemplate($template);
        // render de chaque bloque
        $subject = $template->renderBlock('sujet', $context);
        $html    = $template->renderBlock('corps_html', $context);
        var_dump($html);
        // Nettoyage du code HTML pour la partie texte
        $texte = trim(strip_tags(substr($html, strpos($html, '<font face="arial" style="font-size: 12px;">'), strpos($html, '</body>'))));

        //  Création du message
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->mailer_from)
            ->setTo($emailDestinaire);

        // Ajout du corps si nécessaire
        if (!empty($htmlBody)) {
            $message->setBody($html, 'text/html')
                ->addPart($texte, 'text/plain');
        } else {
            $message->setBody($html);
        }

        // Ajout d'une pièce jointe
        if (!empty($fichiers)) {
            foreach ($fichiers as $fichier) {
                $message->attach(\Swift_Attachment::fromPath($fichier));
            }
        }

        return $this->mailer->send($message);
    }
}