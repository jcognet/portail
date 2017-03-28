<?php

namespace DeviseBundle\Service;

use CommunBundle\Service\MailerService;
use Doctrine\ORM\EntityManager;
use TransverseBundle\Traits\OutputTrait;

/**
 * Class AlertService
 * @package DeviseBundle\Service
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
        foreach ($this->em->getRepository('DeviseBundle:SuiviDevise')->findSuiviARelancer() as $suivi) {
            try {
                // Eet-ce que la devise êst dans les valeurs de seuil ?
                $envoieMail = (
                    ($suivi->getDevise()->getCoursJour() >= $suivi->getSeuilMin() && ($suivi->getSeuilMax() <= 0 || is_null($suivi->getSeuilMax()))) ||
                    ($suivi->getDevise()->getCoursJour() <= $suivi->getSeuilMax() && ($suivi->getSeuilMin() <= 0 || is_null($suivi->getSeuilMin()))) ||
                    ($suivi->getDevise()->getCoursJour() >= $suivi->getSeuilMin() && $suivi->getDevise()->getCoursJour() <= $suivi->getSeuilMax())
                );
                if ($envoieMail) {
                    // Envoi du mail
                    $this->ms->envoieEmail('CommunBundle:Email:alert.html.twig', array(
                        'suivi'  => $suivi,
                        'devise' => $suivi->getDevise(),
                        'user'   => $suivi->getUser(),
                    ), $suivi->getUser());
                    // Mise à jour des données
                    $suivi->setDateAlerte($now);
                    // On n'oublie pas l'utilisateur
                    if ($suivi->getUser()->getJoursAvantRelance() > 0) {
                        $prochainEnvoie = clone($suivi->getUser()->getJourProchaineAlerte());
                        $prochainEnvoie->add(new \DateInterval('P' . $suivi->getUser()->getJoursAvantRelance() . 'D'))
                            ->setTime(0, 0, 0);
                        $suivi->getUser()->setJourProchaineAlerte($prochainEnvoie);
                        $this->em->persist($suivi->getUser());
                    } elseif (!is_null($suivi->getUser()->getJourProchaineAlerte())) {
                        $suivi->getUser()->setJourProchaineAlerte(null);
                        $this->em->persist($suivi->getUser());
                    }
                    $this->em->persist($suivi);
                    $this->em->flush(); // Flush pour relancer l'alerte en cas de problème
                    $this->ecrit("Fin de l'alerte utilisateur avec id : " . $suivi->getUser()->getId() . " - email : " . $suivi->getUser()->getEmail());
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

