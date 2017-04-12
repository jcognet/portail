<?php

namespace CommunBundle\Twig;

use CommunBundle\Entity\News;
use CommunBundle\Service\NewsService;

class NewsExtension extends \Twig_Extension
{

    /**
     * Service de news
     * @var NewsService|null
     */
    protected $newsService = null;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_picto', array($this, 'getPicto')),
        );
    }

    /**
     * Récupère le picto d'une news
     * @param News $news
     * @return null|string
     */
    public function getPicto(News $news){
        return $this->newsService->getPicto($news);
    }

}