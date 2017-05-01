<?php

namespace LivreBundle\Service;

use Doctrine\ORM\EntityManager;
use LivreBundle\Form\LieuType;


/**
 * Class LieuService
 * @package LivreBundle\Service
 */
class LieuService
{
    /**
     * @var EntityManager|null
     */
    protected $em = null;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Constante de type de lieu maison
     */
    const TYPE_LIEU_MAISON = 'maison';

    /**
     * Constante de type de lieu pièce
     */
    const TYPE_LIEU_PIECE = 'piece';

    /**
     * Constante de type de lieu meuble
     */
    const TYPE_LIEU_MEUBLE = 'meuble';

    /**
     * Constante de type de lieu etagere
     */
    const TYPE_LIEU_ETAGERE = 'etagere';

    /**
     * Retourne la liste des types de lieu
     * @return array
     */
    public static function getTypesLieux()
    {
        return array(
            'maison'  => self::TYPE_LIEU_MAISON,
            'pièce'   => self::TYPE_LIEU_PIECE,
            'meuble'  => self::TYPE_LIEU_MEUBLE,
            'etagère' => self::TYPE_LIEU_ETAGERE,
        );
    }

    /**
     * Retourne le form type à partir d'un label type de lieu
     * @param $typeLieu
     * @return string
     */
    public function getFormFromTypeLieu($typeLieu)
    {
        return LieuType::getCurrentNamespace() . "\\" . $this->nettoieTypeLieu($typeLieu) . "Type";
    }

    /**
     * Retourne une entité à partir de son type et de son id
     * @param $typeLieu
     * @param $id
     * @return null|object
     */
    public function getEntityFromTypeLieu($typeLieu, $id)
    {
        return $this->em->getRepository('LivreBundle:' . $this->nettoieTypeLieu($typeLieu))->find($id);
    }

    /**
     * nettoie le type de lieu
     * @param $typeLieu
     * @return string
     */
    protected function nettoieTypeLieu($typeLieu)
    {
        return ucfirst(strtolower(trim($typeLieu)));
    }

    /**
     * Retourne le type de lieu enfant d'un type de lieu
     * @param $typeLieu
     * @return string
     * @throws \Exception
     */
    public function getTypeLieuForFils($typeLieu){
        $typeLieuEnfant ='';
        switch (strtolower(trim($typeLieu))){
            case self::TYPE_LIEU_MAISON:
                $typeLieuEnfant =self::TYPE_LIEU_PIECE;
                break;
            case self::TYPE_LIEU_PIECE:
                $typeLieuEnfant =self::TYPE_LIEU_MEUBLE;
                break;
            case self::TYPE_LIEU_MEUBLE:
                $typeLieuEnfant =self::TYPE_LIEU_ETAGERE;
                break;
            default:
                throw new \Exception("Le type de lieu ".$typeLieu." ne peut pas avoir d'enfants.");
        }
        return $typeLieuEnfant;
    }
}

