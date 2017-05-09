<?php

namespace LivreBundle\Service;
use LivreBundle\Entity\BaseLivre;
use LivreBundle\Entity\Livre;
use Symfony\Component\Filesystem\Filesystem;
use TransverseBundle\Service\CurlService;


/**
 * Class LivreService
 * @package LivreBundle\Service
 */
class LivreImageService
{
    /**
     * Répertoire web des images
     * @var string
     */
    protected $pathWeb = '';

    /**
     * Répertoire d'upload absolu des images
     * @var string
     */
    protected $pathUploadAbsolu = '';

    /**
     * Curl service
     * @var null|CurlService
     */
    protected $curlService = null;

    public function __construct($pathWeb, $pathUploadAbsolu, CurlService $curlService)
    {
        // Répertoire web des images
        $this->pathWeb    = $pathWeb;
        // Ajout d'un / final si nécessaire
        if(substr($this->pathWeb, -1) != '/')
            $this->pathWeb.= '/';
        // Répertorie d'upload des images
        $this->pathUploadAbsolu    = $pathUploadAbsolu;
        if(substr($this->pathUploadAbsolu, -1) != '/')
            $this->pathUploadAbsolu.= '/';
        // Autres propriétés
        $this->curlService   = $curlService;
    }


    /**
     * Récupère l'url d'une image d'un livre
     * @param BaseLivre $livre
     * @return null|string
     */
    public function getUrlImage(BaseLivre $livre){
        // Le path est en relatf à partir de web
        $outUrl = null;
        $fs = new Filesystem();
        if($fs->exists($this->pathUploadAbsolu . $livre->getSlug() . '.jpg')){
            $outUrl = $this->pathWeb.$livre->getSlug().'.jpg';
        }
        return $outUrl;
    }

    /**
     * Enregistre l'url d'une image pour un livre
     * @param BaseLivre $livre
     * @param $urlImage
     * @return bool|\Symfony\Component\HttpFoundation\File\File
     */
    public function enregistreImage(BaseLivre $livre, $urlImage){
        $pathEnregistrement = $this->getPathUploadImage($livre);
        return $this->curlService->telechargeImage($urlImage, $pathEnregistrement);
    }

    /**
     * Retourne le répertoire d'upload d'une image d'un livre
     * @param BaseLivre $livre
     * @return string
     */
    public function getPathUploadImage(BaseLivre $livre){
        return $this->pathUploadAbsolu . $livre->getSlug() . '.jpg';
    }
}

