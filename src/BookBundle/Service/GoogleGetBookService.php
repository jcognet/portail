<?php

namespace BookBundle\Service;

use BookBundle\Entity\Auteur;
use BookBundle\Entity\BaseLivre;
use BookBundle\Entity\Categorie;
use BookBundle\Entity\Editeur;
use BookBundle\Entity\LivreLogWerservice;
use Doctrine\ORM\EntityManager;
use TransverseBundle\Service\CurlService;
use TransverseBundle\Traits\OutputTrait;

/**
 * Class AlertService
 * @package CommunBundle\Service
 */
class GoogleGetBookService
{
    /**
     * URL d'appel de Google API
     */
    const GOOGLE_API_URL = 'https://www.googleapis.com/books/v1/volumes';


    /**
     * Liste des images à utiliser par google api (plus la valeur est au début de la liste, plus elle sera utilisée)
     */
    const IMAGE_SIZE_LISTE = array("medium", "small", "large", "thumbnail");

    /**
     * Constante google avec la clé permettant de retrouver ISBN_13
     */
    const GOOGLE_TYPE_ISBN_13 = "ISBN_13";

    /**
     * Id de la valeur ISBN_13
     */
    const GOOGLE_TYPE_ISBN_13_IDENTIFIER = "identifier";

    /**
     *  Constante google avec la clé permettant de retrouver ISBN_10
     */
    const GOOGLE_TYPE_ISBN_10 = "ISBN_10";

    /**
     * Id de la valeur ISBN_10
     */
    const GOOGLE_TYPE_ISBN_10_IDENTIFIER = "identifier";

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
     * Curl service
     * @var null|CurlService
     */
    protected $curlService = null;

    /**
     * Répertoire d'upload des images
     * @var string
     */
    protected $pathUpload = '';


    public function __construct(EntityManager $em, $googleApiBook, CurlService $curlService, $pathUpload)
    {
        $this->em            = $em;
        $this->googleApiBook = $googleApiBook;
        $this->curlService   = $curlService;
        $this->pathUpload    = $pathUpload;
    }

    /**
     * Recherche un livre suivant un isbn
     * @param $isbn
     * @return BaseLivre
     */
    public function rechercheLivreParISBN($isbn)
    {
        // Préparation du log
        $log  = $this->creeLog($isbn);
        $book = $this->appelleGoogleApi($isbn);
        // Fin enregistrement du log
        $this->finLog($log, $book);
        // Si plus de livre, on arrête le traitement car il y a plusieurs livres avec le même isbn
        $this->ecrit("Nombre de livres : " . $book->totalItems);
        // Ca y est, on peu créer le livre
        return $this->analyseRetourGoogle($book);
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
    protected function appelleGoogleApi($isbn)
    {
        // Création de la requête API
        $query = $this->getGoogleApiUrl($isbn);
        return $this->appelleCurl($query);
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

    /**
     * Analyse le retour de google
     * @param $retourGoogle
     * @return BaseLivre
     */
    public function analyseRetourGoogle($retourGoogle)
    {
        //TODO : what happens si autre monnaie ?
        //TODO : est-ce que la référence de google est unique pour un éditeur & auteur ? Ou création table synonyme
        //TODO : gestion volumeSeries (ajout d'une entité série pour prendre + d'éléments par la suite)
        // Récupération du livre courant
        $book = current($retourGoogle->items);
        // Création du livre à partir du contenu de google
        $livre = $this->convertitGoogleLivre($book);
        $this->em->persist($livre);
        $this->em->flush(); // On flush maintenant pour pouvoir avoir le slug de suite
        // Création de la liste des auteurs
        $listeAuteurs = $this->convertitGoogleAuteurs($book, $livre);
        $this->ecrit("Nombre d'auteurs : " . count($listeAuteurs));
        foreach ($listeAuteurs as $auteur) {
            $this->ecrit("Auteur : " . $auteur->getReferenceGoogle() . " - id : " . $auteur->getId());
        }
        // Création de la liste des éditeurs
        $editeur = $this->convertitGoogleEditeur($book, $livre);
        $this->ecrit("Editeur : " . $editeur->getReferenceGoogle() . " - id : " . $editeur->getId());
        // Gestion des images
        $image = $this->convertitImage($book, $livre);
        // Gestion des catégories
        $listeCategories = $this->convertitCategories($book, $livre);
        foreach ($listeCategories as $categorie) {
            $this->ecrit("Catégorie : " . $categorie->getReferenceGoogle() . " - id : " . $categorie->getId());
        }
        // Enregistrement en base
        $this->em->flush();
        return $livre;
    }

    /***
     * Convertit un retour google en livre
     * @param $livreGoogle
     * @return BaseLivre
     */
    protected function convertitGoogleLivre($livreGoogle)
    {
        // Création du livre si nécessaire
        if (true === is_null($livre = $this->em->getRepository('BookBundle:BaseLivre')->findOneByGoogleId($livreGoogle->id))) {
            $livre = new BaseLivre();
            $livre->setGoogleId($livreGoogle->id)
                ->setDateCreation(new \DateTime());
        }
        // On met à jour le lien
        $livre->setGoogleLink($livreGoogle->selfLink);
        if (true === property_exists($livreGoogle, 'volumeInfo')) {
            # volumeInfo
            $livre->setTitre($livreGoogle->volumeInfo->title)
                ->setDatePublication(new \DateTime($livreGoogle->volumeInfo->publishedDate))
                ->setDescription($livreGoogle->volumeInfo->description)
                ->setNombrePages($livreGoogle->volumeInfo->pageCount)
                ->setPays($livreGoogle->volumeInfo->language);
            // Mise à jour de l'isbn
            if (true === property_exists($livreGoogle->volumeInfo, 'industryIdentifiers')) {
                foreach ($livreGoogle->volumeInfo->industryIdentifiers as $iid) {
                    $type = $iid->type;
                    if (self::GOOGLE_TYPE_ISBN_10 == $type) {
                        $prop = self::GOOGLE_TYPE_ISBN_10_IDENTIFIER;
                        $livre->setIsbn10($iid->$prop);
                    } elseif (self::GOOGLE_TYPE_ISBN_13 == $type) {
                        $prop = self::GOOGLE_TYPE_ISBN_13_IDENTIFIER;
                        $livre->setIsbn13($iid->$prop);
                    }
                }
            }
        }
        // Mise à jour du prix
        if (true === property_exists($livreGoogle, 'saleInfo')) {
            if (true === property_exists($livreGoogle->saleInfo, 'retailPrice')) {
                $livre->setPrix($livreGoogle->saleInfo->retailPrice->amount);
            }
        }

        return $livre;
    }

    /**
     * Convertit un retour google en auteurs et le livre à un livre
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Auteur[)
     */
    protected function convertitGoogleAuteurs($livreGoogle, BaseLivre $livre)
    {
        $listeAuteurs = array();
        // Suppression de tous les auteurs
        foreach ($livre->getAuteurs() as $auteur)
            $livre->removeAuteur($auteur);
        // Gestion des auteurs
        if (true === property_exists($livreGoogle, 'volumeInfo')
            && true === property_exists($livreGoogle->volumeInfo, 'authors')
            && true === is_array($livreGoogle->volumeInfo->authors)
        ) {
            foreach ($livreGoogle->volumeInfo->authors as $googleAuteur) {
                // Création de l'auteur s'il n'existe pas
                if (true === is_null($auteur = $this->em->getRepository('BookBundle:Auteur')->findOneByReferenceGoogle($googleAuteur))) {
                    $auteur = new Auteur(); // Création maintenant pour l'utiliser dans le synonyme repo
                    // Vérification dans les synonymes
                    $auteur = $this->em->getRepository('BookBundle:Synonyme')->findObjetBySynonyme(get_class($auteur), $googleAuteur);
                    if (true === is_null($auteur)) {
                        // Création si nécessaire
                        $auteur->setDateCreation(new \DateTime())
                            ->setReferenceGoogle($googleAuteur)
                            ->setNomComplet($googleAuteur);
                        $this->em->persist($auteur);
                    }
                }
                // Ajout de l'auteur
                $livre->addAuteur($auteur);
                $listeAuteurs[] = $auteur;
            }
        }
        return $listeAuteurs;
    }

    /**
     * Convertit un retour google en editeur et le livre à un livre
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Editeur|null
     */
    protected function convertitGoogleEditeur($livreGoogle, BaseLivre $livre)
    {
        $editeur = null;
        if (true === property_exists($livreGoogle, 'volumeInfo') &&
            true === property_exists($livreGoogle->volumeInfo, 'publisher')
        ) {
            $googleEditeur = $livreGoogle->volumeInfo->publisher;
            // Gestion de l'éditeur
            if (true === is_null($editeur = $this->em->getRepository('BookBundle:Editeur')->findOneByReferenceGoogle($googleEditeur))) {
                $editeur = new Editeur();// Création maintenant pour l'utiliser dans le synonyme repo
                // Vérification dans les synonymes
                $editeur = $this->em->getRepository('BookBundle:Synonyme')->findObjetBySynonyme(get_class($editeur), $googleEditeur);
                if (true === is_null($editeur)) {
                    $editeur->setDateCreation(new \DateTime())
                        ->setReferenceGoogle($googleEditeur)
                        ->setNom($googleEditeur);
                    $this->em->persist($editeur);
                }
            }
        }
        return $editeur;
    }

    /**
     * Convertit une image
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return bool|null|\Symfony\Component\HttpFoundation\File\File
     */
    protected function convertitImage($livreGoogle, BaseLivre $livre)
    {
        // Récupération du contenu du selfLink qui propose + d'image
        $selfLinkContent = $this->getContentSelfLink($livreGoogle);
        $urlImage        = null;
        if (true === property_exists($selfLinkContent, "volumeInfo")
            && true === property_exists($selfLinkContent->volumeInfo, "imageLinks")
        ) {
            $listeImages = $selfLinkContent->volumeInfo->imageLinks;
            // On parcourt la liste des listes proposées. On utilise la première trouvée
            foreach (self::IMAGE_SIZE_LISTE as $size) {
                if (true === property_exists($listeImages, $size)) {
                    $prop     = $size;
                    $urlImage = $listeImages->$prop;
                    break;
                }
            }
        }
        // Pas d'image => on arrête
        if (true === is_null($urlImage)) {
            $this->ecrit("Pas d'image");
            return null;
        }
        // On télécharge l'image
        $pathEnregistrement = $this->pathUpload . $livre->getSlug() . '.jpg';
        $this->ecrit("Enregistrement de image : " . $urlImage);
        $this->ecrit("Destination image : " . $pathEnregistrement);
        //TODO : redimensionner ?
        return $this->curlService->telechargeImage($urlImage, $pathEnregistrement);

    }

    /**
     * Convertit les catégories
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Categorie[]
     */
    protected function convertitCategories($livreGoogle, BaseLivre $livre)
    {
        $listeCategories = array();
        // Récupération du contenu du selfLink qui propose + de catégories
        $selfLinkContent = $this->getContentSelfLink($livreGoogle);
        // Suppression de tous les auteurs
        foreach ($livre->getCategories() as $categorie)
            $livre->removeCategorie($categorie);
        // Gestion des catégories
        if (true === property_exists($selfLinkContent, 'volumeInfo')
            && true === property_exists($selfLinkContent->volumeInfo, 'categories')
        ) {
            foreach ($selfLinkContent->volumeInfo->categories as $categorieGoogle) {
                // Création de la catégorie s'il n'existe pas
                if (true === is_null($categori = $this->em->getRepository('BookBundle:Categorie')->findOneByReferenceGoogle($categorieGoogle))) {
                    $categorie = new Categorie();
                    $categorie = $this->em->getRepository('BookBundle:Synonyme')->findObjetBySynonyme(get_class($categorie), $categorieGoogle);
                    if (true === is_null($categorie)) {
                        $categorie->setReferenceGoogle($categorieGoogle)
                            ->setLabel($categorieGoogle);
                        $this->em->persist($categorie);
                    }
                }
                // Ajout de l'auteur
                $livre->addCategorie($categorie);
                $listeCategories[] = $categorie;
            }
        }
        return $listeCategories;
    }

    /**
     * Récupère le contenu de self link de google
     * @param $livreGoogle
     * @return mixed|string
     */
    protected function getContentSelfLink($livreGoogle)
    {
        static $contentSelfLink = ""; // Petit mécanisme de cache
        if (false === is_object($contentSelfLink)) {
            $selfLink        = $livreGoogle->selfLink;
            $contentSelfLink = $this->appelleCurl($selfLink);
        }

        return $contentSelfLink;
    }

    /**
     * Appelle une URL CURL
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    protected function appelleCurl($url)
    {
        $this->ecrit('URL : ' . $url);
        return $this->curlService->appelleCurl($url);
    }
}

