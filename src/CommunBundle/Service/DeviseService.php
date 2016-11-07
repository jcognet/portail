<?php

namespace CommunBundle\Service;

use CommunBundle\Entity\CoursJournee;
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


    public function recupereEtSauveCours(array $listeDevise = null, \DateTime $date = null)
    {
        $listeCoursJournee = array();
        foreach ($this->recupereCours($listeDevise, $date) as $devise => $taux) {
            //TODO : vérifier si l'entité existe en base. Si non => création, si oui, récupération
            $cours = new CoursJournee();
            $cours
                ->setDate($date)
                ->setDevise($this->em->getRepository('CommunBundle:Devise')->findOneByCodeWebservice($devise));
            //TODO : calculer moyennes
            $this->em->persist($cours);
        }
        $this->em->flush();
        return $listeCoursJournee;

    }

    /**
     * Recupere les cours d'une liste de devise
     * @param array|null $listeDevise liste des devises à traiter
     * @param \DateTime|null $date jour à récupérer
     * @return array de la forme code devise => $taux / euros
     */
    public function recupereCours(array $listeDevise = null, \DateTime $date = null)
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
        // URL du service
        $urlRequete = strtolower($this->url);
        // Un peu de protection
        if (substr($urlRequete, 0, 4) != 'http') {
            $urlRequete = 'http' . $urlRequete;
        }
        if (substr($urlRequete, -1) != '/') {
            $urlRequete .= '/';
        }
        // Ajout de la date et de la devise
        $urlRequete .= $date->format('Y-m-d') . '?symbols=' . implode(',', $listeDevise);
        $res = json_decode(file_get_contents($urlRequete));
        if (!is_null($res)) {
            $listeTaux = array();
            foreach ($res->rates as $devise => $taux) {
                $listeTaux[$devise] = $taux;
            }
        }
        return $listeTaux;
    }
}