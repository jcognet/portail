<?php

namespace CommunBundle\Service;

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
     * MailerService constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     * @param $mailer_from
     */
    public function __construct( \Twig_Environment $twig, \Swift_Mailer $mailer, $mailer_from)
    {
        $this->mailer            = $mailer;
        $this->twig              = $twig;
        $this->mailer_from       = $mailer_from;
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
        // Création des variables pour twig
        $context  = $this->twig->mergeGlobals($var);
        $template = $this->twig->loadTemplate($template);
        // render de chaque bloque
        $subject  = $template->renderBlock('sujet', $context);
        $texte = $template->renderBlock('corps_text', $context);
        $html = $template->renderBlock('corps_html', $context);
        // Envoi du mail de bienvenue sauf en cas de demande
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->mailer_from)
            ->setTo($destinataire);

        // Ajout du corps si nécessaire
        if (!empty($htmlBody)) {
            $message->setBody($html, 'text/html')
                ->addPart($texte, 'text/plain');
        } else {
            $message->setBody($html);
        }

        // Ajout d'une pièce jointe
        if(!empty($fichiers))
        {
            foreach($fichiers as $fichier)
            {
                $message->attach(\Swift_Attachment::fromPath($fichier));
            }
        }

        return $this->mailer->send($message);
    }
}