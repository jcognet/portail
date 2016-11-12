<?php
/**
 * Created by PhpStorm.
 * User: Jerome
 * Date: 12/11/2016
 * Time: 11:09
 */

namespace CommunBundle\Twig;


use CommunBundle\Entity\Devise;

class DeviseExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('affichePrix', array($this, 'affichePrix')),
        );
    }

    public function affichePrix($valeur)
    {
        return round($valeur, 2, PHP_ROUND_HALF_DOWN);
    }

}