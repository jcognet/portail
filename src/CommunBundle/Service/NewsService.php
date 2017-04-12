<?php

namespace CommunBundle\Service;
use CommunBundle\Entity\News;


/**
 * Service de news
 *
 * Class NewsService
 * @package  CommunBundle\Service
 */
class NewsService
{
    /**
     * Récupère le picto d'une lews
     * @param News $news
     * @return null|string
     */
    public function getPicto(News $news){
        $picto = null;
        switch ($news->getType()){
            case News::TYPE_NEWS_CHANGESOUS:
                $picto ='glyphicon glyphicon-euro';
                break;
            case News::TYPE_NEWS_ALEX:
                $picto ='glyphicon glyphicon-book';
                break;
            default:
                $picto ='glyphicon glyphicon-glass';
        }
        return $picto;
    }
}