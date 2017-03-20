<?php

namespace BookBundle\Service;

use BookBundle\Entity\BaseLivre;
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
        //TODO : protection sur l'isbn
        // Préparation du log
        $log = $this->creeLog($isbn);
        // TODO : enregistrer retour livre en base (2 cas : trouvés et inconnus)
        $bookJSon = $this->appelleGoogle($isbn);
        $book     = json_decode($bookJSon);
        // Fin enregistrement du log
        $this->finLog($log, $book);
        // Si plus de livre, on arrête le traitement car il y a plusieurs livres avec le même isbn
        if (1 != $book->totalItems)
            return false;
        // Ca y est, on peu créer le livre
        //TODO : protège sur unicité isbn
        $this->convertitGoogleBooksEntite($book);
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

    public function convertitGoogleBooksEntite($retourGoogle){
        $book = current($retourGoogle->items);
        $livre = new BaseLivre();
        $livre->setGoogleId($book->id)
            ->setDate
            ->setGoogleLink($book->selfLink)
            # volumeInfo
            ->setTitre($book->volumeInfo->title)
            ->setDatePublication(new \DateTime($book->volumeInfo->publishedDate))
            ->setDescription($book->volumeInfo->description)
            ->setNombrePages($book->volumeInfo->pageCount)
            # saleInfo
            ->setNombrePages($book->saleInfo->retailPrice->amount)
            ;
        //TODO : lien avec auteur
        //TODO : lien avec éditeur
        //TODO : lien avec images
        //TODO : lien avec catégories
        $this->em->persist($livre);
        $this->em->flush();

    }
}

