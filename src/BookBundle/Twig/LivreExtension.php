<?php

namespace BookBundle\Twig;


use BookBundle\Entity\BaseLivre;
use BookBundle\Service\LivreImageService;

class LivreExtension extends \Twig_Extension
{

    /**
     * Service de gestion d'une image
     * @var LivreImageService|null
     */
    protected $imageService = null;

    public function __construct(LivreImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_url_image', array($this, 'getUrlImage')),
        );
    }

    public function getUrlImage(BaseLivre $livre){
        return $this->imageService->getUrlImage($livre);
    }



}