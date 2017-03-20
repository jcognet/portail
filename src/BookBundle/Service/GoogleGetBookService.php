<?php

namespace BookBundle\Service;

use BookBundle\Entity\Auteur;
use BookBundle\Entity\BaseLivre;
use BookBundle\Entity\Editeur;
use BookBundle\Entity\LivreLogWerservice;
use Doctrine\ORM\EntityManager;
use CommunBundle\Traits\OutputTrait;

/**
 * Class AlertService
 * @package CommunBundle\Service
 */
class GoogleGetBookService
{
    const GOOGLE_API_URL = 'https://www.googleapis.com/books/v1/volumes';

    use  OutputTrait;

    /**
     * * Entity manager
     * @var EntityManager|null
     */
    protected $em = null;

    /**
     * Clé Google Api
     * @var string
     */
    protected $googleApiBook = '';


    /**
     * AlertService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, $googleApiBook)
    {
        $this->em            = $em;
        $this->googleApiBook = $googleApiBook;
    }

    public function rechercheLivreParISBN($isbn)
    {
        //TODO : protection sur l'isbn (unicité en base + protection)
        // Préparation du log
        $log      = $this->creeLog($isbn);
        $bookJSon = $this->appelleGoogle($isbn);
        $book     = json_decode($bookJSon);
        // Fin enregistrement du log
        $this->finLog($log, $book);
        // Si plus de livre, on arrête le traitement car il y a plusieurs livres avec le même isbn
        if (1 != $book->totalItems)
            return false;
        // TODO : enregistrer retour livre en base (inconnus)
        // Ca y est, on peu créer le livre
        $this->analyseRetourGoogle($book);
    }

    /**
     * URL de la requête API
     * @return string
     */
    protected function getGoogleApiUrl($isbn)
    {
        return self::GOOGLE_API_URL . '?key=' . $this->googleApiBook . '&q=isbn:' . $isbn;
    }

    /**
     * Appelle Google pour récupérer les informations sur le livre
     * @param $isbn
     * @return mixed
     * @throws \Exception
     */
    protected function appelleGoogle($isbn)
    {
        // Création de la requête API
        $query = $this->getGoogleApiUrl($isbn);
        // Appel Curl
        $resource = curl_init();
        // Prépararation à un appel curl
        curl_setopt($resource, CURLOPT_URL, $query);
        curl_setopt($resource, CURLOPT_HEADER, false);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
        // On y va !
        $book = curl_exec($resource);
        try {
            if (false !== $book) {
                return $book;
            }
        } catch (\Exception $e) {
            echo curl_error($resource);
            curl_close($resource);
            throw $e;
        }
        curl_close($resource);
        throw new \Exception("Impossible de se connecter au webservice de devise pour l'url : " . $query);
    }

    /**
     * Crée le log de l'appel google Api books
     * @param $isbn
     * @return LivreLogWerservice
     */
    protected function creeLog($isbn)
    {
        $log = new LivreLogWerservice();
        $log->setDebut(new \DateTime())
            ->setIsbn($isbn)
            ->setUrl($this->getGoogleApiUrl($isbn));
        $this->em->persist($log);
        $this->em->flush();
        return $log;
    }

    /**
     * Enregiste la fin d'un log
     * @param LivreLogWerservice $log
     * @param $resultat
     */
    protected function finLog(LivreLogWerservice $log, $resultat)
    {
        $log->setDateFin(new \DateTime())
            ->setResultat(serialize($resultat));
        $this->em->persist($log);
        $this->em->flush();
    }

    public function analyseRetourGoogle($retourGoogle)
    {
        //TODO : what happens si autre monnaie ?
        //TODO : est-ce que la référence de google est unique pour un éditeur & auteur ? Ou création table synonyme
        //TODO : ISBN
        //TODO : ISBN 10 & ISBN 13 + unicité
        //TODO : gestion volumeSeries
        //TODO : lien avec images
        //TODO : lien avec catégories
        // Récupération du livre courant
        $book = current($retourGoogle->items);
        $livre = $this->convertitGoogleLivre($book);
        $listeAuteurs =  $this->convertitGoogleAuteurs($book, $livre);
        $editeur = $this->convertitGoogleEditeur($book, $livre);
        $this->em->persist($livre);
        $this->em->flush();
    }

    /***
     * Convertit un retour google en livre
     * @param $livreGoogle
     * @return BaseLivre
     */
    protected function convertitGoogleLivre($livreGoogle){
        // Création du livre
        $livre = new BaseLivre();
        $livre->setGoogleId($livreGoogle->id)
            ->setDateCreation(new \DateTime())
            ->setGoogleLink($livreGoogle->selfLink)
            # volumeInfo
            ->setTitre($livreGoogle->volumeInfo->title)
            ->setDatePublication(new \DateTime($livreGoogle->volumeInfo->publishedDate))
            ->setDescription($livreGoogle->volumeInfo->description)
            ->setNombrePages($livreGoogle->volumeInfo->pageCount)
            ->setPays($livreGoogle->volumeInfo->language)
            # saleInfo
            ->setPrix($livreGoogle->saleInfo->retailPrice->amount);
        return $livre;
    }

    /**
     * Convertit un retour google en auteurs et le livre à un livre
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Auteur[)
     */
    protected function convertitGoogleAuteurs($livreGoogle, BaseLivre $livre){
        $listeAuteurs = array();
        // Gestion des auteurs
        if (true === is_array($livreGoogle->volumeInfo->authors)) {
            foreach ($livreGoogle->volumeInfo->authors as $googleAuteur) {
                // Création de l'auteur s'il n'existe pas
                if (true === is_null($auteur = $this->em->getRepository('BookBundle:Auteur')->findOneByReferenceGoogle($googleAuteur))) {
                    $auteur = new Auteur();
                    $auteur->setDateCreation(new \DateTime())
                        ->setReferenceGoogle($googleAuteur)
                        ->setNomComplet($googleAuteur);
                    $this->em->persist($auteur);
                }
                // Ajout de l'auteur
                $livre->addAuteur($auteur);
                $listeAuteurs[] = $auteur;
            }
        }
        return $listeAuteurs ;
    }

    /**
     * Convertit un retour google en editeur et le livre à un livre
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Editeur|null
     */
    protected function convertitGoogleEditeur($livreGoogle, BaseLivre $livre){
        $editeur = null;
        $googleEditeur = $livreGoogle->volumeInfo->publisher;
        // Gestion de l'éditeur
        if (true === is_null($editeur = $this->em->getRepository('BookBundle:Editeur')->findOneByReferenceGoogle($googleEditeur))) {
            $editeur = new Editeur();
            $editeur->setDateCreation(new \DateTime())
                ->setReferenceGoogle($googleEditeur)
                ->setNom($googleEditeur)
            ;
            $this->em->persist($editeur);
        }
        return $editeur;
    }
}

