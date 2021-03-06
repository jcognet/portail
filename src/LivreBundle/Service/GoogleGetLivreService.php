<?php

namespace LivreBundle\Service;

// TODO : Changer le pays ? https://productforums.google.com/forum/#!topic/books-api/mitOSAavojo pour gérer la monnaie ?
// TODO : redimenssion les images ?

use LivreBundle\Entity\Auteur;
use LivreBundle\Entity\BaseLivre;
use LivreBundle\Entity\Categorie;
use LivreBundle\Entity\Editeur;
use LivreBundle\Entity\LivreLogWerservice;
use LivreBundle\Entity\Serie;
use Doctrine\ORM\EntityManager;
use TransverseBundle\Service\CurlService;
use TransverseBundle\Traits\OutputTrait;

/**
 * Class GoogleGetLivreService
 * @package LivreBundle\Service
 */
class GoogleGetLivreService
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
     * Service gérant les livres
     * @var LivreImageService
     */
    protected $livreService = '';

    /**
     * Entité de log d'appel au webservice google
     * @var LivreLogWerservice
     */
    protected $log = null;

    /**
     * URL d'appel google
     * @var string
     */
    protected $url = '';


    public function __construct(EntityManager $em, $googleApiBook, CurlService $curlService, LivreImageService $livreService)
    {
        $this->em            = $em;
        $this->googleApiBook = $googleApiBook;
        $this->curlService   = $curlService;
        $this->livreService  = $livreService;
    }

    /**
     * Recherche un livre suivant un isbn
     * @param $isbn
     * @return BaseLivre
     */
    public function rechercheLivreParISBN($isbn)
    {
        $this->resetCache();
        // Préparation du log
        $this->creeLog($isbn);
        $livre = $this->appelleGoogleApi($isbn);
        // Si plus de livre, on arrête le traitement car il y a plusieurs livres avec le même isbn
        $this->ecrit("Nombre de livres : " . $livre->totalItems);
        // Ca y est, on peu créer le livre
        return $this->analyseRetourGoogle($livre);
    }

    /**
     * URL de la requête API avec un q
     * @return string
     */
    protected function getPrimeGoogleApiUrl($isbn)
    {
        return self::GOOGLE_API_URL . '?key=' . $this->googleApiBook . '&q=isbn:' . $isbn;
    }

    /**
     * URL de la requête API avec un q
     * @return string
     */
    protected function getSecondaryGoogleApiUrl($isbn)
    {
        return self::GOOGLE_API_URL . '?key=' . $this->googleApiBook . '&q=' . $isbn;
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
        $query        = $this->getPrimeGoogleApiUrl($isbn);
        $retourGoogle = $this->appelleCurl($query);
        // Si le premier appel n'a rien envoyé, on tente la deuxième URL
        if (0 === $retourGoogle->totalItems) {
            $query        = $this->getSecondaryGoogleApiUrl($isbn);
            $retourGoogle = $this->appelleCurl($query);
        }
        // On enregistre l'url pour les logs
        $this->url = $query;
        return $retourGoogle;
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
            ->setUrl($this->url);
        $this->em->persist($log);
        $this->em->flush();
        $this->log = $log;
        return $log;
    }

    /**
     * Enregiste la fin d'un log
     * @param $resultat
     * @param $resultatSelfLink
     */
    protected function finLog($resultat, $resultatSelfLink)
    {
        $this->log->setDateFin(new \DateTime())
            ->setResultat(serialize($resultat))
            ->setResultatSelfContent(serialize($resultatSelfLink));
        $this->em->persist($this->log);
        $this->em->flush();
    }

    /**
     * Analyse le retour de google
     * @param $retourGoogle
     * @return BaseLivre
     */
    protected function analyseRetourGoogle($retourGoogle)
    {
        // PRotection
        if (0 === $retourGoogle->totalItems)
            return;
        // Récupération du livre courant
        $bookPremier = current($retourGoogle->items);
        $book        = $this->getContentSelfLink($bookPremier);
        // Fin enregistrement du log
        $this->finLog($book, $bookPremier);
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
        $serie = $this->convertitSeries($book, $livre);
        if (false === is_null($serie))
            $this->ecrit("Série : " . $serie->getReferenceGoogle() . " - id : " . $serie->getId());
        // Enregistrement en basee
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
        if (true === is_null($livre = $this->em->getRepository('LivreBundle:BaseLivre')->findOneByGoogleId($livreGoogle->id))) {
            $livre = new BaseLivre();
            $livre->setGoogleId($livreGoogle->id)
                ->setDateCreation(new \DateTime());
        }
        // On met à jour le lien
        $livre->setGoogleLink($livreGoogle->selfLink);
        if (true === property_exists($livreGoogle, 'volumeInfo')) {
            # volumeInfo
            $livre->setTitre($livreGoogle->volumeInfo->title)
                ->setDatePublication(new \DateTime($livreGoogle->volumeInfo->publishedDate));
            if (true === property_exists($livreGoogle->volumeInfo, 'description'))
                $livre->setDescription($livreGoogle->volumeInfo->description);
            $livre->setNombrePages($livreGoogle->volumeInfo->pageCount)
                ->setPays($livreGoogle->volumeInfo->language)
                ->setGoogleDetailLink($livreGoogle->volumeInfo->previewLink);;
            # Dimension
            if (true === property_exists($livreGoogle->volumeInfo, 'dimensions')) {
                if (true === property_exists($livreGoogle->volumeInfo->dimensions, 'height'))
                    $livre->setHauteur($livreGoogle->volumeInfo->dimensions->height);
                if (true === property_exists($livreGoogle->volumeInfo->dimensions, 'width'))
                    $livre->setLargeur($livreGoogle->volumeInfo->dimensions->width);
                if (true === property_exists($livreGoogle->volumeInfo->dimensions, 'thickness'))
                    $livre->setEpaisseur($livreGoogle->volumeInfo->dimensions->thickness);
            }
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
                if (true === is_null($auteur = $this->em->getRepository('LivreBundle:Auteur')->findOneByReferenceGoogle($googleAuteur))) {
                    $auteur = new Auteur(); // Création maintenant pour l'utiliser dans le synonyme repo
                    // Vérification dans les synonymes
                    $auteur = $this->em->getRepository('LivreBundle:Synonyme')->findObjetBySynonyme(get_class($auteur), $googleAuteur);
                    if (true === is_null($auteur)) {
                        $auteur = new Auteur();
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
            if (true === is_null($editeur = $this->em->getRepository('LivreBundle:Editeur')->findOneByReferenceGoogle($googleEditeur))) {
                $editeur = new Editeur();// Création maintenant pour l'utiliser dans le synonyme repo
                // Vérification dans les synonymes
                $editeur = $this->em->getRepository('LivreBundle:Synonyme')->findObjetBySynonyme(get_class($editeur), $googleEditeur);
                if (true === is_null($editeur)) {
                    $editeur = new Editeur();
                    $editeur->setDateCreation(new \DateTime())
                        ->setReferenceGoogle($googleEditeur)
                        ->setNom($googleEditeur);
                    $this->em->persist($editeur);
                }

            }
            // Ajout de l'éditeur au livre
            if (false === is_null($editeur))
                $livre->setEditeur($editeur);

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
        $urlImage = null;
        if (true === property_exists($livreGoogle, "volumeInfo")
            && true === property_exists($livreGoogle->volumeInfo, "imageLinks")
        ) {
            $listeImages = $livreGoogle->volumeInfo->imageLinks;
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
        $this->ecrit("Enregistrement de image : " . $urlImage);
        $this->ecrit("Destination image : " . $this->livreService->getPathUploadImage($livre));
        return $this->livreService->enregistreImage($livre, $urlImage);

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
        // Suppression de tous les auteurs
        foreach ($livre->getCategories() as $categorie)
            $livre->removeCategorie($categorie);
        // Gestion des catégories
        if (true === property_exists($livreGoogle, 'volumeInfo')
            && true === property_exists($livreGoogle->volumeInfo, 'categories')
        ) {
            foreach ($livreGoogle->volumeInfo->categories as $categorieGoogle) {
                // Création de la catégorie s'il n'existe pas
                if (true === is_null($categorie = $this->em->getRepository('LivreBundle:Categorie')->findOneByReferenceGoogle($categorieGoogle))) {
                    $categorie = new Categorie();
                    $categorie = $this->em->getRepository('LivreBundle:Synonyme')->findObjetBySynonyme(get_class($categorie), $categorieGoogle);
                    if (true === is_null($categorie)) {
                        $categorie = new Categorie();
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
     * Convertit la série du livre
     * @param $livreGoogle
     * @param BaseLivre $livre
     * @return Serie|null
     */
    public function convertitSeries($livreGoogle, BaseLivre $livre)
    {
        $serie = null;
        if (true === property_exists($livreGoogle, "volumeInfo")
            && true === property_exists($livreGoogle->volumeInfo, 'seriesInfo')
        ) {
            // Récupération de la référence de google
            $seriesGoogle         = current($livreGoogle->volumeInfo->seriesInfo->volumeSeries);
            $serieReferenceGoogle = $seriesGoogle->seriesId;
            // Requête
            $serie = $this->em->getRepository('LivreBundle:Serie')->findOneByReferenceGoogle($serieReferenceGoogle);
            if (true === is_null($serie)) {
                $serie = new Serie();
                $serie->setReferenceGoogle($serieReferenceGoogle)
                    ->setNom($livre->getTitre());
                $this->em->persist($serie);
            }
            $serie->addLivre($livre);
            $livre->setSerie($serie);

        }
        return $serie;
    }

    /**
     * Récupère le contenu de self link de google
     * @param $livreGoogle
     * @return mixed|string
     */
    protected function getContentSelfLink($livreGoogle)
    {
        if (false === is_object($this->contentSelfLink)) {
            $selfLink        = $livreGoogle->selfLink;
            $this->contentSelfLink = $this->appelleCurl($selfLink);
        }

        return $this->contentSelfLink;
    }

    /**
     * @var string
     */
    protected $contentSelfLink = "";

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


    /**
     * Remet à 0 le cache de l'objet
     */
    public function resetCache(){
        $this->contentSelfLink = "";
    }
}

