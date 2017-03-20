<?php

namespace CommunBundle\Service;

use CommunBundle\Entity\CoursJournee;
use CommunBundle\Entity\Devise;
use Doctrine\ORM\EntityManager;

/**
 * Service qui gère les devises
 *
 * Class DeviseService
 * @package  CommunBundle\Service
 */
class DeviseService
{

    /**
     * Entity manager
     * @var EntityManager|null
     */
    private $em = null;


    /**
     * URL du webservice
     * @var string
     */
    private $url = "";

    public function __construct(EntityManager $em, $url)
    {
        $this->em  = $em;
        $this->url = $url;
    }


    /**
     * Recupère et sauve les cours
     * @param array|null $listeDevise Liste des devises à traiter
     * @param \DateTime|null $date Date du traitement
     * @return array
     */
    public function recupereEtSauveCours(array $listeDevise = null, \DateTime $date = null)
    {
        // Récupération de toutes les devises si 0 paramètre
        if (is_null($listeDevise) || count($listeDevise) == 0) {
            $listeDevise = array();
            foreach ($this->em->getRepository('CommunBundle:Devise')->findAll() as $devise) {
                $listeDevise[] = $devise->getCode();
            }
        }
        if (count($listeDevise) == 0) {
            return array();
        }
        foreach ($listeDevise as $key => &$devise) {
            $listeDevise[$key] = strtoupper($devise);
        }
        // Protection sur la date
        if (is_null($date)) {
            $date = new \DateTime('yesterday');
        }

        $listeCoursJournee = array();
        foreach ($this->recupereCours($listeDevise, $date) as $codeDevise => $taux) {
            // Création ou récupération en base
            $devise = $this->em->getRepository('CommunBundle:Devise')->findOneByCodeWebservice($codeDevise);
            if (true === is_null($cours = $this->em->getRepository('CommunBundle:CoursJournee')->findOneBy(
                array(
                    'date'   => $date,
                    'devise' => $devise
                )
            )))
                $cours = new CoursJournee();
            // Données par défaut
            $cours
                ->setDate($date)
                ->setDevise($devise)
                ->setCours($taux)
                ->setMoyenne30Jours($this->em->getRepository('CommunBundle:CoursJournee')->getMoyenneCours($date, 30, $devise))
                ->setMoyenne60Jours($this->em->getRepository('CommunBundle:CoursJournee')->getMoyenneCours($date, 60, $devise))
                ->setMoyenne90Jours($this->em->getRepository('CommunBundle:CoursJournee')->getMoyenneCours($date, 90, $devise))
                ->setMoyenne120Jours($this->em->getRepository('CommunBundle:CoursJournee')->getMoyenneCours($date, 120, $devise));
            $this->em->persist($cours);

        }
        $this->em->flush();
        return $listeCoursJournee;
    }

    /**
     * Met à jour une devise à partir du cours le + récent
     * @param Devise $devise
     */
    public function updateCoursJoursDevise(Devise $devise)
    {
        $listeCours      = $this->em->getRepository('CommunBundle:CoursJournee')->findBy(
            array('devise' => $devise),
            array('date' => 'DESC'),
            1
        );
        $coursPlusRecent = reset($listeCours);
        // Mise à jour de la devise
        $devise->setCoursJour($coursPlusRecent->getCours());
        $devise->setJour($coursPlusRecent->getDate());
        $devise->setMoyenne30Jours($coursPlusRecent->getMoyenne30Jours());
        $devise->setMoyenne60Jours($coursPlusRecent->getMoyenne60Jours());
        $devise->setMoyenne90Jours($coursPlusRecent->getMoyenne90Jours());
        $devise->setMoyenne120Jours($coursPlusRecent->getMoyenne120Jours());
        $this->em->persist($devise);
        $this->em->flush();
    }


    /**
     * Met à jours toutes les devises de l'application
     * @return bool
     */
    public function updateCoursTouteDevise()
    {
        foreach ($this->em->getRepository('CommunBundle:Devise')->findAll() as $devise) {
            $this->updateCoursJoursDevise($devise);
        }
        return true;
    }

    /**
     * Recupere les cours d'une liste de devise
     * @param array|null $listeDevise liste des devises à traiter
     * @param \DateTime|null $date jour à récupérer
     * @return array de la forme code devise => $taux / euros
     * @throws \Exception
     */
    private function recupereCours(array $listeDevise = null, \DateTime $date = null)
    {
        $urlRequete = $this->url;
        // Ajout de la date et de la devise
        $urlRequete .= $date->format('Y-m-d') . '?symbols=' . implode(',', $listeDevise);
        $resource = curl_init();
        // Prépararation à un appel curl
        curl_setopt($resource, CURLOPT_URL, $urlRequete);
        curl_setopt($resource,  CURLOPT_HEADER, false);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        // On y va !
        $serialized = curl_exec($resource);
        try {
            if (false !== $serialized ) {
                $listeTaux = array();
                $res = json_decode($serialized, true);
                if (false === is_null($res)) {
                    foreach ($res['rates'] as $devise => $taux) {
                        $listeTaux[$devise] = $taux;
                    }
                }
                curl_close($resource);
                return $listeTaux;

            }
        } catch (\Exception $e) {
            echo curl_error ( $resource);
            curl_close($resource);
            throw $e;
        }
        curl_close($resource);
        throw new \Exception("Impossible de se connecter au webservice de devise pour l'url : " . $urlRequete);

    }
}