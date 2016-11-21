<?php

namespace CommunBundle\Service;

use Doctrine\ORM\EntityManager;
use CommunBundle\Traits\OutputTrait;

/**
 * Class AlertService
 * @package CommunBundle\Service
 */
class AlertService
{
    use  OutputTrait;

    /**
     * * Entity manager
     * @var EntityManager|null
     */
    protected $em = null;

    /**
     * Service de mailer
     * @var MailerService|null
     */
    protected $ms = null;


    /**
     * AlertService constructor.
     * @param EntityManager $em
     * @param MailerService $ms
     */
    public function __construct(EntityManager $em, MailerService $ms)
    {
        $this->em = $em;
        $this->ms = $ms;
    }


    /**
     * Prévient les utilisateurs quand nécessaires
     * @return array
     */
    public function previentUtilisateurs()
    {
        $listeErreurs = array();
        $now          = new \DateTime();
        foreach ($this->em->getRepository('CommunBundle:SuiviDevise')->findSuiviARelancer() as $suivi) {
            try {
                if ($suivi->getDevise()->getCoursJour() >= $suivi->getSeuilMin()
                    || $suivi->getDevise()->getCoursJour() <= $suivi->getSeuilMax()
                ) {
                    // Envoi du mail
                    $this->ms->envoieEmail('CommunBundle:Email:alert.html.twig', array(
                        'suivi'  => $suivi,
                        'devise' => $suivi->getDevise(),
                        'user'   => $suivi->getUser(),
                    ), $suivi->getUser());
                    // Mise à jour des données
                    $suivi->setDateAlerte($now);
                    $this->em->persist($suivi);
                    $this->em->flush(); // Flush pour relancer l'erreur en cas de problème
                    $this->ecrit("Fin de l'alerte utilisateur avec id : " . $suivi->getId());
                }
            } catch (\Exception $ex) {
                $listeErreurs[] = $ex;
            }
        }
        // Affichage des erreurs
        if (count($listeErreurs) > 0) {
            $this->ecritErreur("Nombre d'erreurs : " + count($listeErreurs));
            foreach ($listeErreurs as $erreur) {
                $this->ecritErreur($erreur->getMessage());
                $this->ecritErreur($erreur->getFile());
                $this->ecritErreur($erreur->getCode());
                $this->ecritErreur($erreur->getTraceAsString());
                $this->ecritErreur('**********************');
            }
        }
        $this->ecritSucces('fini');
        return $listeErreurs;
    }

}

