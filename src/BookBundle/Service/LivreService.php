<?php

namespace BookBundle\Service;
use Symfony\Component\Filesystem\Filesystem;


/**
 * Class LivreService
 * @package BookBundle\Service
 */
class LivreService
{
    /**
     * Répertoire d'upload des images
     * @var string
     */
    protected $pathUpload = '';

    public function __construct($pathUpload)
    {
        // Si possible garder l'ordre de GoogleGetBookService
        $this->pathUpload    = $pathUpload;
    }


    public function getUrlImage(Livre $livre){
        //TODO : changer le path ici
        $outUrl = null;
        $fs = new Filesystem();
        if($fs->exists($this->pathUpload . $livre->getSlug() . '.jpg')){
            $outUrl = '//'.$livre->getSlug().'.jpg';
        }
        return $outUrl;
    }
}

